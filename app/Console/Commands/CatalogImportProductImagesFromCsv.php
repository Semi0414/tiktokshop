<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Download product images from URLs in a CSV and store on local public disk + product_images.
 *
 * Typical CSV: sku,imgUrl1,imgUrl2,... or product_id,image_url1,...
 */
class CatalogImportProductImagesFromCsv extends Command
{
    protected $signature = 'catalog:import-images-from-csv
        {--csv= : Path to CSV (relative to project base or absolute)}
        {--match=sku : sku | product_id — how to find the product row}
        {--match-column=sku : CSV column name for sku or numeric product id}
        {--sku-prefix= : Optional prefix prepended to match-column value (e.g. TG-)}
        {--image-columns= : Comma-separated column names; empty = auto-detect imgUrl1, imgUrl2, image_url1, ...}
        {--limit=0 : Max CSV data rows (0 = all)}
        {--offset=0 : Skip first N data rows}
        {--max-images=20 : Max images to save per product}
        {--replace : Delete existing product_images for that product before import}
        {--sleep-ms=50 : Pause between HTTP requests (milliseconds)}
        {--timeout=25 : HTTP timeout seconds}
    ';

    protected $description = 'Download remote image URLs from CSV into local storage and attach to products (product_images).';

    public function handle(): int
    {
        $csvPath = (string) $this->option('csv');
        if ($csvPath === '') {
            $this->error('Pass --csv=path/to/file.csv');

            return self::FAILURE;
        }

        if (! str_starts_with($csvPath, '/') && ! preg_match('#^[A-Za-z]:\\\\#', $csvPath)) {
            $csvPath = base_path($csvPath);
        }

        if (! is_file($csvPath)) {
            $this->error("CSV not found: {$csvPath}");

            return self::FAILURE;
        }

        $match = strtolower(trim((string) $this->option('match')));
        if (! in_array($match, ['sku', 'product_id'], true)) {
            $this->error('--match must be sku or product_id');

            return self::FAILURE;
        }

        $matchColumn = trim((string) $this->option('match-column'));
        if ($matchColumn === '') {
            $matchColumn = $match === 'product_id' ? 'product_id' : 'sku';
        }

        $skuPrefix = (string) $this->option('sku-prefix');

        $imageColumnsOpt = trim((string) $this->option('image-columns'));
        $limit = (int) $this->option('limit');
        $offset = max(0, (int) $this->option('offset'));
        $maxImages = max(1, (int) $this->option('max-images'));
        $replace = (bool) $this->option('replace');
        $sleepMs = max(0, (int) $this->option('sleep-ms'));
        $timeout = max(5, (int) $this->option('timeout'));

        $handle = fopen($csvPath, 'r');
        if (! $handle) {
            $this->error('Cannot open CSV');

            return self::FAILURE;
        }

        $header = fgetcsv($handle);
        if (! is_array($header) || $header === []) {
            fclose($handle);
            $this->error('Missing CSV header');

            return self::FAILURE;
        }

        $header = array_map(static fn ($h) => trim((string) $h), $header);

        if (! in_array($matchColumn, $header, true)) {
            fclose($handle);
            $this->error("Column \"{$matchColumn}\" not found in CSV. Available: ".implode(', ', $header));

            return self::FAILURE;
        }

        $imageColumns = $this->resolveImageColumns($header, $imageColumnsOpt);
        if ($imageColumns === []) {
            fclose($handle);
            $this->error('No image URL columns found. Use --image-columns=col1,col2 or add imgUrl1, image_url1, etc.');

            return self::FAILURE;
        }

        $this->info('CSV: '.$csvPath);
        $this->info('Match: '.$match.' ← column "'.$matchColumn.'"'.($skuPrefix !== '' ? ' (prefix: '.$skuPrefix.')' : ''));
        $this->info('Image columns: '.implode(', ', $imageColumns));

        $stats = [
            'rows_seen' => 0,
            'products_matched' => 0,
            'products_not_found' => 0,
            'images_saved' => 0,
            'images_failed' => 0,
            'rows_no_urls' => 0,
        ];

        $rowNum = 0;
        $processed = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            if ($offset > 0 && $rowNum <= $offset) {
                continue;
            }
            if ($limit > 0 && $processed >= $limit) {
                break;
            }
            $processed++;
            $stats['rows_seen']++;

            $assoc = [];
            foreach ($header as $i => $key) {
                $assoc[$key] = $row[$i] ?? null;
            }

            $rawKey = trim((string) ($assoc[$matchColumn] ?? ''));
            if ($rawKey === '') {
                continue;
            }

            if ($match === 'product_id') {
                $productId = (int) $rawKey;
                if ($productId < 1 || ! DB::table('products')->where('id', $productId)->exists()) {
                    $stats['products_not_found']++;

                    continue;
                }
            } else {
                $lookup = $skuPrefix.$rawKey;
                $productId = (int) (DB::table('products')->where('sku', $lookup)->value('id') ?? 0);
                if ($productId < 1) {
                    $stats['products_not_found']++;

                    continue;
                }
            }

            $stats['products_matched']++;

            if (! $replace) {
                $has = DB::table('product_images')->where('product_id', $productId)->exists();
                if ($has) {
                    continue;
                }
            }

            $urls = [];
            foreach ($imageColumns as $col) {
                $u = trim((string) ($assoc[$col] ?? ''));
                if ($u !== '' && filter_var($u, FILTER_VALIDATE_URL)) {
                    $urls[] = $u;
                }
            }
            $urls = array_values(array_unique($urls));
            $urls = array_slice($urls, 0, $maxImages);

            if ($urls === []) {
                $stats['rows_no_urls']++;

                continue;
            }

            if ($replace) {
                DB::table('product_images')->where('product_id', $productId)->delete();
            }

            $position = 1;
            foreach ($urls as $url) {
                try {
                    $response = Http::timeout($timeout)
                        ->retry(2, 300)
                        ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; BagistoImageImport/1.0)'])
                        ->get($url);

                    if (! $response->successful()) {
                        $stats['images_failed']++;

                        continue;
                    }

                    $body = $response->body();
                    if ($body === '' || strlen($body) < 100) {
                        $stats['images_failed']++;

                        continue;
                    }

                    $ext = $this->guessExtension($url, (string) $response->header('Content-Type', ''));
                    $relativePath = 'product/imported/'.date('Y/m').'/'.$productId.'_'.substr(sha1($url), 0, 12).'.'.$ext;

                    Storage::disk('public')->put($relativePath, $body);

                    DB::table('product_images')->insert([
                        'type' => null,
                        'path' => $relativePath,
                        'product_id' => $productId,
                        'position' => $position,
                    ]);

                    $position++;
                    $stats['images_saved']++;

                    if ($sleepMs > 0) {
                        usleep($sleepMs * 1000);
                    }
                } catch (\Throwable) {
                    $stats['images_failed']++;
                }
            }
        }

        fclose($handle);

        $this->newLine();
        $this->info('Finished.');
        foreach ($stats as $k => $v) {
            $this->line("{$k}: {$v}");
        }

        return self::SUCCESS;
    }

    /**
     * @param  array<int, string>  $header
     * @return array<int, string>
     */
    private function resolveImageColumns(array $header, string $explicit): array
    {
        if ($explicit !== '') {
            $wanted = array_map('trim', explode(',', $explicit));

            return array_values(array_filter($wanted, static fn ($c) => $c !== '' && in_array($c, $header, true)));
        }

        $cols = [];
        foreach ($header as $col) {
            if (preg_match('/^(imgUrl|image_url|imageUrl|image|photo|picture)\d*$/i', $col)) {
                $cols[] = $col;
            }
        }

        usort($cols, static function ($a, $b) {
            preg_match('/(\d+)$/', $a, $am);
            preg_match('/(\d+)$/', $b, $bm);

            return ((int) ($am[1] ?? 0)) <=> ((int) ($bm[1] ?? 0));
        });

        return array_values(array_unique($cols));
    }

    private function guessExtension(string $url, string $contentType): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: '';
        $extFromPath = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($extFromPath, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp'], true)) {
            return $extFromPath === 'jpeg' ? 'jpg' : $extFromPath;
        }

        return match (true) {
            str_contains($contentType, 'image/png') => 'png',
            str_contains($contentType, 'image/webp') => 'webp',
            str_contains($contentType, 'image/gif') => 'gif',
            str_contains($contentType, 'image/bmp') => 'bmp',
            default => 'jpg',
        };
    }
}
