<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TiktokImportProductImages extends Command
{
    protected $signature = 'tiktok:import-images
        {--csv=exports/dhgyiu_all_products_flattened.csv : Path to the TikTok Mall CSV}
        {--limit=0 : 0 means all rows}
        {--offset=0 : Number of data rows to skip before importing}
        {--max-images=1 : Maximum images to import per product}
        {--replace : Replace existing images for matched products}
        {--sleep-ms=0 : Sleep between downloads in milliseconds}
    ';

    protected $description = 'Download product images from CSV imgUrl columns and map them to product_images.';

    public function handle(): int
    {
        $csvPath = (string) $this->option('csv');
        $limit = (int) $this->option('limit');
        $offset = max(0, (int) $this->option('offset'));
        $maxImages = max(1, (int) $this->option('max-images'));
        $replace = (bool) $this->option('replace');
        $sleepMs = max(0, (int) $this->option('sleep-ms'));

        if (! str_starts_with($csvPath, '/')) {
            $csvPath = base_path($csvPath);
        }

        if (! is_file($csvPath)) {
            $this->error("CSV not found: {$csvPath}");

            return self::FAILURE;
        }

        $this->info("Image import starting: {$csvPath}");
        $this->info("Row window: offset={$offset}, limit={$limit}");
        $this->info("Max images per product: {$maxImages}");
        $this->info('Replace existing: '.($replace ? 'yes' : 'no'));

        $handle = fopen($csvPath, 'r');

        if (! $handle) {
            $this->error("Unable to open CSV: {$csvPath}");

            return self::FAILURE;
        }

        $header = fgetcsv($handle);

        if (! is_array($header) || empty($header)) {
            fclose($handle);
            $this->error('CSV header row missing or invalid.');

            return self::FAILURE;
        }

        $header = array_map(static fn ($h) => trim((string) $h), $header);

        $imageColumns = array_values(array_filter(
            $header,
            static fn ($column) => (bool) preg_match('/^imgUrl[0-9]+$/i', (string) $column)
        ));

        usort($imageColumns, static function ($a, $b) {
            preg_match('/([0-9]+)$/', $a, $aMatch);
            preg_match('/([0-9]+)$/', $b, $bMatch);

            return ((int) ($aMatch[1] ?? 0)) <=> ((int) ($bMatch[1] ?? 0));
        });

        if (empty($imageColumns)) {
            fclose($handle);
            $this->warn('No imgUrl columns found in CSV.');

            return self::SUCCESS;
        }

        $this->info('Using image columns: '.implode(', ', $imageColumns));

        $stats = [
            'rows_seen' => 0,
            'products_matched' => 0,
            'images_saved' => 0,
            'images_failed' => 0,
            'products_without_urls' => 0,
            'products_not_found' => 0,
            'skipped_already_has_images' => 0,
        ];

        $processedRows = 0;
        $rowNum = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            if ($offset > 0 && $rowNum <= $offset) {
                continue;
            }

            if ($row === [null] || $row === false) {
                continue;
            }

            if ($limit > 0 && $processedRows >= $limit) {
                break;
            }

            $processedRows++;
            $stats['rows_seen']++;

            $assoc = [];
            $headerCount = count($header);
            for ($i = 0; $i < $headerCount; $i++) {
                $assoc[$header[$i]] = $row[$i] ?? null;
            }

            // Same SKU rule as TiktokImportCatalog: CSV id is sellerGoodsId; goodsId is parent key when present.
            $sellerGoodsId = trim((string) ($assoc['id'] ?? ''));
            if ($sellerGoodsId === '') {
                continue;
            }

            $parentGoodsId = trim((string) ($assoc['goodsId'] ?? ''));
            $productGroupKey = $parentGoodsId !== '' ? $parentGoodsId : $sellerGoodsId;

            $sku = 'TG-'.$productGroupKey;

            $productId = (int) (DB::table('products')->where('sku', $sku)->value('id') ?? 0);

            if (! $productId) {
                $stats['products_not_found']++;

                continue;
            }

            $stats['products_matched']++;

            if (! $replace) {
                $alreadyHasImage = DB::table('product_images')
                    ->where('product_id', $productId)
                    ->exists();

                if ($alreadyHasImage) {
                    $stats['skipped_already_has_images']++;

                    continue;
                }
            }

            $urls = [];
            foreach ($imageColumns as $column) {
                $url = trim((string) ($assoc[$column] ?? ''));

                if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL)) {
                    $urls[] = $url;
                }
            }

            $urls = array_values(array_unique($urls));
            $urls = array_slice($urls, 0, $maxImages);

            if (empty($urls)) {
                $stats['products_without_urls']++;

                continue;
            }

            if ($replace) {
                DB::table('product_images')->where('product_id', $productId)->delete();
            }

            $position = 1;

            foreach ($urls as $url) {
                try {
                    $response = Http::timeout(20)
                        ->retry(2, 250)
                        ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                        ->get($url);

                    if (! $response->successful()) {
                        $stats['images_failed']++;

                        continue;
                    }

                    $contentType = (string) $response->header('Content-Type', '');
                    $ext = $this->guessExtension($url, $contentType);

                    $relativePath = 'product/tiktok/'.date('Y/m').'/'.$productId.'_'.sha1($url).'.'.$ext;
                    Storage::disk('public')->put($relativePath, $response->body());

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

        $this->info('Image import finished.');
        foreach ($stats as $key => $value) {
            $this->line($key.': '.$value);
        }

        return self::SUCCESS;
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
