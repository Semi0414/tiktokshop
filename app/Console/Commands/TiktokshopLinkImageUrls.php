<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Streams the TikTok Mall product CSV exports and links each imported product
 * to its remote S3 image URLs WITHOUT downloading the binaries.
 *
 * For every CSV row:
 *  - The SKU is reconstructed using the same rule as `TiktokImportCatalog`:
 *    `'TG-' . (goodsId ?: id)`.
 *  - All non-empty `imgUrl1..imgUrl10` values are taken in order and inserted
 *    into `product_images.path` directly as URLs.
 *
 * This is dramatically faster than `tiktok:import-images` (which actually
 * downloads bytes), and it makes the data grid show real images for the
 * 19k+ imported products by just rendering the S3 URL via an `<img src>`.
 *
 * The data grid closure was updated to recognise `path` values starting with
 * `http://` or `https://` and emit them as-is.
 */
class TiktokshopLinkImageUrls extends Command
{
    protected $signature = 'tiktokshop:link-image-urls
        {--file=* : One or more CSV paths (defaults to the two known files under exports/)}
        {--max-images=10 : Maximum images per product to link (1..10)}
        {--replace : Wipe existing product_images for matched products before linking}
        {--only-missing : Skip products that already have any product_images row}
        {--dry-run : Print intended changes without writing}
        {--progress-every=200000 : Refresh progress every N rows}';

    protected $description = 'Link product_images to remote S3 URLs from the TikTok CSV (no download).';

    protected array $defaultFiles = [
        'exports/dhgyiu_all_products_flattened.csv',
        'exports/dhgyiu_all_products_flattened_with_detail_variants_partial_800pages.csv',
    ];

    public function handle(): int
    {
        $files = $this->resolveFiles();

        if (empty($files)) {
            $this->error('No CSV files found. Pass --file=<path> or place exports under '.base_path('exports').'.');

            return self::FAILURE;
        }

        $maxImages = max(1, min(10, (int) $this->option('max-images')));
        $replace = (bool) $this->option('replace');
        $onlyMissing = (bool) $this->option('only-missing');
        $dryRun = (bool) $this->option('dry-run');
        $progressEvery = max(10000, (int) $this->option('progress-every'));

        $this->info('Building SKU => [imgUrl..] map ...');

        $map = [];

        foreach ($files as $file) {
            $this->scanFile($file, $map, $progressEvery, $maxImages);
        }

        $this->info('Captured '.count($map).' unique SKU image URL bundles.');

        if (empty($map)) {
            $this->warn('No data; nothing to link.');

            return self::SUCCESS;
        }

        $changed = $this->applyToDatabase($map, $replace, $onlyMissing, $dryRun);

        $this->info(($dryRun ? '[dry-run] would link ' : 'Linked ').$changed.' product image rows.');

        return self::SUCCESS;
    }

    /**
     * Resolve the list of files to scan.
     *
     * @return list<string>
     */
    protected function resolveFiles(): array
    {
        $opt = (array) $this->option('file');

        if (empty($opt)) {
            $opt = $this->defaultFiles;
        }

        $resolved = [];

        foreach ($opt as $file) {
            $path = $file;

            if (! str_starts_with($path, DIRECTORY_SEPARATOR)
                && ! preg_match('#^[A-Za-z]:[\\\\/]#', $path)) {
                $path = base_path($file);
            }

            if (is_file($path)) {
                $resolved[] = $path;
            } else {
                $this->warn("CSV not found, skipping: {$path}");
            }
        }

        return $resolved;
    }

    /**
     * Stream a CSV file and append `sku => [url1, url2, ...]` mappings into $map.
     *
     * Uses a fast prefix parser that captures only the first ~46 columns
     * (id, goodsId at idx 0/1, imgUrl1..10 at idx 35..44) and discards the rest
     * of every row.
     */
    protected function scanFile(string $path, array &$map, int $progressEvery, int $maxImages): void
    {
        $this->newLine();
        $this->info("Scanning: {$path}");

        $size = @filesize($path);

        if ($size !== false) {
            $this->line('  size: '.$this->humanBytes($size));
        }

        $handle = fopen($path, 'rb');

        if (! $handle) {
            $this->warn('  unable to open file.');

            return;
        }

        $maxColumns = 50; // need at most idx 44 (imgUrl10); safety: 50.
        $rows = $this->streamPrefixRows($handle, $maxColumns);

        $header = $rows->current();

        if (! is_array($header)) {
            $this->warn('  empty / unreadable header.');
            fclose($handle);

            return;
        }

        if (isset($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        }

        $headerIndex = array_flip($header);
        $idxId = $headerIndex['id'] ?? null;
        $idxGoodsId = $headerIndex['goodsId'] ?? null;

        if ($idxId === null && $idxGoodsId === null) {
            $this->warn('  no id / goodsId column found. Skipping file.');
            fclose($handle);

            return;
        }

        $imgIdx = [];

        for ($n = 1; $n <= 10; $n++) {
            if (isset($headerIndex['imgUrl'.$n])) {
                $imgIdx[$n] = $headerIndex['imgUrl'.$n];
            }
        }

        if (empty($imgIdx)) {
            $this->warn('  no imgUrl* columns found. Skipping file.');
            fclose($handle);

            return;
        }

        $rows->next();

        $rowCount = 0;
        $skuMatched = 0;
        $startedAt = microtime(true);
        $beforeCount = count($map);

        while ($rows->valid()) {
            $row = $rows->current();
            $rows->next();

            $rowCount++;

            $sellerGoodsId = isset($row[$idxId]) ? trim((string) $row[$idxId]) : '';
            $parentGoodsId = $idxGoodsId !== null && isset($row[$idxGoodsId])
                ? trim((string) $row[$idxGoodsId])
                : '';

            $key = $parentGoodsId !== '' ? $parentGoodsId : $sellerGoodsId;

            if ($key === '') {
                continue;
            }

            $sku = 'TG-'.$key;

            if (isset($map[$sku])) {
                /**
                 * The same parent SKU appears repeatedly across variant rows.
                 * The first row already gave us the canonical image URLs.
                 */
                continue;
            }

            $urls = [];

            foreach ($imgIdx as $i) {
                $url = isset($row[$i]) ? trim((string) $row[$i]) : '';

                if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL)) {
                    $urls[] = $url;

                    if (count($urls) >= $maxImages) {
                        break;
                    }
                }
            }

            if (empty($urls)) {
                continue;
            }

            $map[$sku] = $urls;
            $skuMatched++;

            if ($rowCount % $progressEvery === 0) {
                $elapsed = max(0.001, microtime(true) - $startedAt);
                $rate = (int) ($rowCount / $elapsed);

                $this->output->write(sprintf(
                    "\r  rows: %s | new SKUs: %s | total: %s | %s rows/s",
                    number_format($rowCount),
                    number_format($skuMatched),
                    number_format(count($map)),
                    number_format($rate)
                ));
            }
        }

        fclose($handle);

        $this->newLine();
        $this->info(sprintf(
            '  done. rows scanned: %s, new SKUs from this file: %s, total: %s',
            number_format($rowCount),
            number_format(count($map) - $beforeCount),
            number_format(count($map))
        ));
    }

    /**
     * Apply the SKU => [urls] map to product_images. Inserts URL rows as paths.
     *
     * @return int  number of image rows inserted (or that would be inserted).
     */
    protected function applyToDatabase(array $map, bool $replace, bool $onlyMissing, bool $dryRun): int
    {
        $skus = array_keys($map);
        $totalSkus = count($skus);

        $this->info("Resolving {$totalSkus} SKUs against products table ...");

        $skuToProductId = [];

        foreach (array_chunk($skus, 1000) as $chunk) {
            $rows = DB::table('products')
                ->whereIn('sku', $chunk)
                ->select('id', 'sku')
                ->get();

            foreach ($rows as $row) {
                $skuToProductId[$row->sku] = (int) $row->id;
            }
        }

        $this->info('Matched '.count($skuToProductId).' / '.$totalSkus.' SKUs to product rows.');

        if (empty($skuToProductId)) {
            return 0;
        }

        if ($onlyMissing) {
            $existing = DB::table('product_images')
                ->whereIn('product_id', array_values($skuToProductId))
                ->pluck('product_id')
                ->unique()
                ->all();

            $skip = array_flip($existing);

            $skuToProductId = array_filter(
                $skuToProductId,
                static fn ($pid) => ! isset($skip[$pid])
            );

            $this->info('After --only-missing filter: '.count($skuToProductId).' SKUs to process.');
        }

        $hasTimestamps = Schema::hasColumn('product_images', 'created_at');

        $totalInserted = 0;
        $bar = $this->output->createProgressBar(count($skuToProductId));
        $bar->start();

        foreach (array_chunk($skuToProductId, 500, true) as $chunk) {
            $insertRows = [];
            $productIdsToWipe = [];

            foreach ($chunk as $sku => $productId) {
                $urls = $map[$sku] ?? [];

                if (empty($urls)) {
                    continue;
                }

                if ($replace) {
                    $productIdsToWipe[] = $productId;
                }

                $position = 1;

                foreach ($urls as $url) {
                    $row = [
                        'type' => 'images',
                        'path' => $url,
                        'product_id' => $productId,
                        'position' => $position,
                    ];

                    if ($hasTimestamps) {
                        $row['created_at'] = now();
                        $row['updated_at'] = now();
                    }

                    $insertRows[] = $row;
                    $position++;
                }
            }

            if (! empty($productIdsToWipe) && ! $dryRun) {
                DB::table('product_images')
                    ->whereIn('product_id', $productIdsToWipe)
                    ->delete();
            }

            if (! empty($insertRows)) {
                if ($dryRun) {
                    $totalInserted += count($insertRows);
                } else {
                    DB::table('product_images')->insert($insertRows);
                    $totalInserted += count($insertRows);
                }
            }

            $bar->advance(count($chunk));
        }

        $bar->finish();
        $this->newLine();

        return $totalInserted;
    }

    /**
     * Generator yielding first $maxColumns of each CSV row, with full quote
     * tracking across the entire row so embedded newlines/commas are safe.
     *
     * @return \Generator<int, array<int, string>>
     */
    protected function streamPrefixRows($handle, int $maxColumns): \Generator
    {
        $cols = [];
        $cur = '';
        $inQuote = false;
        $atFieldStart = true;
        $colDone = false;
        $hasContent = false;
        $bufSize = 1 << 16;

        while (! feof($handle)) {
            $buf = fread($handle, $bufSize);

            if ($buf === false || $buf === '') {
                break;
            }

            $len = strlen($buf);

            for ($i = 0; $i < $len; $i++) {
                $ch = $buf[$i];

                if ($inQuote) {
                    if ($ch === '"') {
                        if ($i + 1 < $len) {
                            $next = $buf[$i + 1];
                        } else {
                            $next = false;

                            if (! feof($handle)) {
                                $peek = fgetc($handle);

                                if ($peek !== false) {
                                    $buf .= $peek;
                                    $len++;
                                    $next = $peek;
                                }
                            }
                        }

                        if ($next === '"') {
                            if (! $colDone) {
                                $cur .= '"';
                            }
                            $i++;

                            continue;
                        }

                        $inQuote = false;

                        continue;
                    }

                    if (! $colDone) {
                        $cur .= $ch;
                    }

                    continue;
                }

                if ($atFieldStart) {
                    $atFieldStart = false;

                    if ($ch === '"') {
                        $inQuote = true;

                        continue;
                    }
                }

                if ($ch === ',') {
                    if (! $colDone) {
                        $cols[] = $cur;
                        $cur = '';

                        if (count($cols) >= $maxColumns) {
                            $colDone = true;
                        }
                    }
                    $atFieldStart = true;
                    $hasContent = true;

                    continue;
                }

                if ($ch === "\n" || $ch === "\r") {
                    if ($hasContent || $cur !== '' || ! empty($cols)) {
                        if (! $colDone) {
                            $cols[] = $cur;
                        }

                        yield $cols;
                    }

                    $cols = [];
                    $cur = '';
                    $colDone = false;
                    $hasContent = false;
                    $atFieldStart = true;

                    if ($ch === "\r" && $i + 1 < $len && $buf[$i + 1] === "\n") {
                        $i++;
                    }

                    continue;
                }

                if (! $colDone) {
                    $cur .= $ch;
                }
                $hasContent = true;
            }
        }

        if ($hasContent || $cur !== '' || ! empty($cols)) {
            if (! $colDone) {
                $cols[] = $cur;
            }

            yield $cols;
        }
    }

    protected function humanBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return number_format($bytes, 2).' '.$units[$i];
    }
}
