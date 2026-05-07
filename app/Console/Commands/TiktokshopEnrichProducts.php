<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Streams the TikTok product CSV exports and back-fills the missing per-product
 * data for SKU rows that already exist in `products` but whose `product_flat` /
 * `product_attribute_values` are still NULL (e.g. CSV-imported placeholders).
 *
 * Populates:
 *   - product_attribute_values  (name, url_key, description, price, status,
 *     visible_individually, new, featured)
 *   - product_flat              (denormalized cache used by storefront grids)
 *   - product_inventories       (default qty = --default-qty, DB-side default 100)
 *   - product_categories        (mapped via categories.additional.tiktok_category_id)
 *   - product_channels          (assign to default channel)
 *
 * The command is idempotent: existing PAV rows for the targeted attributes are
 * deleted then re-inserted; product_flat / product_inventories are upserted.
 *
 * Designed to be MUCH faster than re-running `tiktok:import-catalog` because
 * it uses the prefix-only streaming parser (constant memory regardless of how
 * wide the rows are).
 */
class TiktokshopEnrichProducts extends Command
{
    protected $signature = 'tiktokshop:enrich-products
        {--file=* : One or more CSV paths (defaults to the two known files under exports/)}
        {--locale=en : Locale to write attribute values for}
        {--default-qty=100 : Default inventory qty when CSV has none}
        {--default-stock-source=1 : product_inventories.inventory_source_id}
        {--only-missing : Skip products that already have a populated product_flat name}
        {--dry-run : Print what would be written without touching the DB}
        {--progress-every=20000 : Refresh progress every N rows}
        {--limit=0 : Stop after N rows (0 = no limit)}';

    protected $description = 'Back-fill name / price / status / qty / category for CSV-imported product placeholder rows.';

    protected array $defaultFiles = [
        'exports/dhgyiu_all_products_flattened.csv',
        'exports/dhgyiu_all_products_flattened_with_detail_variants_partial_800pages.csv',
    ];

    public function handle(): int
    {
        $files = $this->resolveFiles();

        if (empty($files)) {
            $this->error('No CSV files found.');

            return self::FAILURE;
        }

        $locale = (string) $this->option('locale');
        $defaultQty = max(0, (int) $this->option('default-qty'));
        $stockSourceId = max(1, (int) $this->option('default-stock-source'));
        $onlyMissing = (bool) $this->option('only-missing');
        $dryRun = (bool) $this->option('dry-run');
        $progressEvery = max(1000, (int) $this->option('progress-every'));
        $limit = max(0, (int) $this->option('limit'));

        $channel = core()->getDefaultChannel();
        $channelId = (int) $channel->id;
        $channelCode = (string) ($channel->code ?: 'default');

        $this->info('Building parent-SKU => details map ...');

        $map = [];
        $rowsScanned = 0;

        foreach ($files as $file) {
            $this->scanFile($file, $map, $progressEvery, $limit > 0 ? max(0, $limit - $rowsScanned) : 0, $rowsScanned);

            if ($limit > 0 && $rowsScanned >= $limit) {
                break;
            }
        }

        $this->info('Captured details for '.count($map).' unique parent SKUs.');

        if (empty($map)) {
            $this->warn('Nothing to update.');

            return self::SUCCESS;
        }

        $this->applyToDatabase($map, $locale, $channelId, $channelCode, $defaultQty, $stockSourceId, $onlyMissing, $dryRun);

        return self::SUCCESS;
    }

    /**
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

    protected function scanFile(string $path, array &$map, int $progressEvery, int $rowsBudget, int &$totalRowsScanned): void
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

        $maxColumns = 50; // categoryId/Name + sellingPrice + name + des + isShelf + imgUrl..
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

        $hi = array_flip($header);

        $cols = [
            'id' => $hi['id'] ?? null,
            'goodsId' => $hi['goodsId'] ?? null,
            'name' => $hi['name'] ?? null,
            'sellingPrice' => $hi['sellingPrice'] ?? null,
            'systemPrice' => $hi['systemPrice'] ?? null,
            'categoryId' => $hi['categoryId'] ?? null,
            'secondaryCategoryId' => $hi['secondaryCategoryId'] ?? null,
            'isShelf' => $hi['isShelf'] ?? null,
            'soldNum' => $hi['soldNum'] ?? null,
            'des' => $hi['des'] ?? null,
        ];

        $rows->next();

        $rowCount = 0;
        $newSkus = 0;
        $startedAt = microtime(true);
        $beforeCount = count($map);

        while ($rows->valid()) {
            if ($rowsBudget > 0 && $rowCount >= $rowsBudget) {
                break;
            }

            $row = $rows->current();
            $rows->next();
            $rowCount++;
            $totalRowsScanned++;

            $get = static fn (string $field) => $cols[$field] !== null ? trim((string) ($row[$cols[$field]] ?? '')) : '';

            $sellerGoodsId = $get('id');
            $parentGoodsId = $get('goodsId');
            $key = $parentGoodsId !== '' ? $parentGoodsId : $sellerGoodsId;

            if ($key === '') {
                continue;
            }

            $sku = 'TG-'.$key;
            $name = $get('name');
            $sellingPrice = $get('sellingPrice');
            $systemPrice = $get('systemPrice');
            $categoryId = $get('categoryId');
            $secondaryCategoryId = $get('secondaryCategoryId');
            $isShelf = $get('isShelf');
            $des = $get('des');

            $price = $sellingPrice !== '' ? (float) $sellingPrice
                : ($systemPrice !== '' ? (float) $systemPrice : null);

            $existing = $map[$sku] ?? null;

            if ($existing === null) {
                $map[$sku] = [
                    'sku' => $sku,
                    'name' => $name !== '' ? $name : null,
                    'price' => $price,
                    'status' => $isShelf !== '' ? (int) ((bool) (int) $isShelf) : 1,
                    'description' => $des !== '' ? $des : null,
                    'category_top_id' => $categoryId !== '' ? $categoryId : null,
                    'category_secondary_id' => $secondaryCategoryId !== '' && $secondaryCategoryId !== '0' ? $secondaryCategoryId : null,
                ];
                $newSkus++;
            } else {
                /**
                 * Variant rows: keep best name/description/price (lowest price >0).
                 */
                if ($existing['name'] === null && $name !== '') {
                    $map[$sku]['name'] = $name;
                }

                if ($existing['description'] === null && $des !== '') {
                    $map[$sku]['description'] = $des;
                }

                if ($price !== null && $price > 0) {
                    if ($existing['price'] === null || $price < $existing['price']) {
                        $map[$sku]['price'] = $price;
                    }
                }

                if ($existing['category_top_id'] === null && $categoryId !== '') {
                    $map[$sku]['category_top_id'] = $categoryId;
                }

                if ($existing['category_secondary_id'] === null && $secondaryCategoryId !== '' && $secondaryCategoryId !== '0') {
                    $map[$sku]['category_secondary_id'] = $secondaryCategoryId;
                }
            }

            if ($rowCount % $progressEvery === 0) {
                $elapsed = max(0.001, microtime(true) - $startedAt);
                $rate = (int) ($rowCount / $elapsed);

                $this->output->write(sprintf(
                    "\r  rows: %s | new SKUs: %s | total: %s | %s rows/s",
                    number_format($rowCount),
                    number_format($newSkus),
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

    protected function applyToDatabase(
        array $map,
        string $locale,
        int $channelId,
        string $channelCode,
        int $defaultQty,
        int $stockSourceId,
        bool $onlyMissing,
        bool $dryRun,
    ): void {
        $skus = array_keys($map);
        $totalSkus = count($skus);

        $this->info("Resolving {$totalSkus} SKUs against products table ...");

        $skuToProductId = [];
        $productFamily = [];

        foreach (array_chunk($skus, 1000) as $chunk) {
            $rows = DB::table('products')
                ->whereIn('sku', $chunk)
                ->select('id', 'sku', 'attribute_family_id')
                ->get();

            foreach ($rows as $row) {
                $skuToProductId[$row->sku] = (int) $row->id;
                $productFamily[(int) $row->id] = (int) ($row->attribute_family_id ?: 1);
            }
        }

        $this->info('Matched '.count($skuToProductId).' / '.$totalSkus.' SKUs to product rows.');

        if ($onlyMissing) {
            $alreadyPopulated = DB::table('product_flat')
                ->whereIn('product_id', array_values($skuToProductId))
                ->whereNotNull('name')
                ->where('name', '<>', '')
                ->pluck('product_id')
                ->unique()
                ->all();

            $skip = array_flip($alreadyPopulated);
            $skuToProductId = array_filter($skuToProductId, static fn ($pid) => ! isset($skip[$pid]));
            $this->info('After --only-missing filter: '.count($skuToProductId).' SKUs to process.');
        }

        if (empty($skuToProductId)) {
            return;
        }

        // Resolve attribute ids and value-column for each code.
        $attrCodes = ['name', 'url_key', 'description', 'price', 'status', 'visible_individually', 'new', 'featured'];

        $attributes = DB::table('attributes')
            ->whereIn('code', $attrCodes)
            ->get()
            ->keyBy('code');

        $typeToCol = [
            'text' => 'text_value',
            'textarea' => 'text_value',
            'price' => 'float_value',
            'boolean' => 'boolean_value',
            'select' => 'integer_value',
            'multiselect' => 'text_value',
            'datetime' => 'datetime_value',
            'date' => 'date_value',
            'file' => 'text_value',
            'image' => 'text_value',
            'checkbox' => 'text_value',
        ];

        // Resolve TikTok category id => Bagisto category id mapping (from `additional` JSON).
        $categoryByTiktok = [];
        $catRows = DB::table('categories')
            ->whereNotNull('additional')
            ->where('additional', '<>', '')
            ->select('id', 'additional')
            ->get();

        foreach ($catRows as $c) {
            $a = json_decode((string) $c->additional, true);
            $tid = is_array($a) ? ($a['tiktok_category_id'] ?? null) : null;

            if ($tid) {
                $categoryByTiktok[$tid] = (int) $c->id;
            }
        }

        $bar = $this->output->createProgressBar(count($skuToProductId));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->setMessage('writing PAV/flat/inv/cat');
        $bar->start();

        $now = now();
        $nowString = $now->toDateTimeString();

        $attributeIds = array_values(array_map(static fn ($a) => (int) $a->id, $attributes->all()));

        // 200 SKUs * 8 attribute codes = ~1600 PAV rows per insert, well under
        // MySQL default max_allowed_packet (4MB) even with long descriptions.
        $chunkSize = 200;

        foreach (array_chunk($skuToProductId, $chunkSize, true) as $chunk) {
            if ($dryRun) {
                $bar->advance(count($chunk));

                continue;
            }

            $writer = function () use (
                $chunk, $map, $attributes, $attributeIds, $typeToCol, $locale, $channelCode,
                $channelId, $defaultQty, $stockSourceId, $categoryByTiktok, $productFamily, $nowString
            ) {
                $productIds = array_values($chunk);

                /**
                 * Wipe existing PAV rows we are about to rewrite (idempotent).
                 */
                if (! empty($attributeIds)) {
                    DB::table('product_attribute_values')
                        ->whereIn('product_id', $productIds)
                        ->whereIn('attribute_id', $attributeIds)
                        ->delete();
                }

                $pavRows = [];
                $flatRows = [];
                $invRows = [];
                $catRows = [];
                $channelsRows = [];

                foreach ($chunk as $sku => $productId) {
                    $info = $map[$sku] ?? null;

                    if (! $info) {
                        continue;
                    }

                    $name = (string) ($info['name'] ?? '') ?: 'Product '.$sku;
                    if (mb_strlen($name) > 250) {
                        $name = mb_substr($name, 0, 250);
                    }

                    $price = (float) ($info['price'] ?? 0);
                    $status = (int) ($info['status'] ?? 1);
                    $description = (string) ($info['description'] ?? '');
                    /**
                     * Trim description to a reasonable size (~16KB) to keep batched
                     * INSERT packet sizes well under MySQL's default max_allowed_packet
                     * (4MB). Full HTML descriptions for TikTok products are still huge.
                     */
                    if (strlen($description) > 16000) {
                        $description = mb_strcut($description, 0, 16000, 'UTF-8');
                    }

                    $urlKeyBase = Str::slug($name) ?: ('product-'.$productId);
                    $urlKey = $urlKeyBase.'-'.$productId;

                    $values = [
                        'name' => $name,
                        'url_key' => $urlKey,
                        'description' => $description,
                        'price' => $price,
                        'status' => (bool) $status,
                        'visible_individually' => true,
                        'new' => false,
                        'featured' => false,
                    ];

                    foreach ($values as $code => $val) {
                        if (! isset($attributes[$code])) {
                            continue;
                        }
                        $attr = $attributes[$code];
                        $col = $typeToCol[$attr->type] ?? null;

                        if (! $col) {
                            continue;
                        }

                        $pavRow = [
                            'product_id' => (int) $productId,
                            'attribute_id' => (int) $attr->id,
                            'channel' => $attr->value_per_channel ? $channelCode : null,
                            'locale' => $attr->value_per_locale ? $locale : null,
                            'text_value' => null,
                            'boolean_value' => null,
                            'integer_value' => null,
                            'float_value' => null,
                            'datetime_value' => null,
                            'date_value' => null,
                            'json_value' => null,
                        ];
                        $pavRow[$col] = $val;
                        $pavRows[] = $pavRow;
                    }

                    $flatRows[] = [
                        'sku' => $sku,
                        'type' => 'configurable',
                        'product_number' => '',
                        'name' => $name,
                        'short_description' => null,
                        'description' => $description !== '' ? $description : null,
                        'url_key' => $urlKey,
                        'new' => 0,
                        'featured' => 0,
                        'status' => $status,
                        'meta_title' => $name,
                        'meta_keywords' => null,
                        'meta_description' => null,
                        'price' => $price,
                        'special_price' => null,
                        'special_price_from' => null,
                        'special_price_to' => null,
                        'weight' => null,
                        'created_at' => $nowString,
                        'locale' => $locale,
                        'channel' => $channelCode,
                        'attribute_family_id' => $productFamily[(int) $productId] ?? 1,
                        'product_id' => (int) $productId,
                        'updated_at' => $nowString,
                        'parent_id' => null,
                        'visible_individually' => 1,
                    ];

                    $invRows[] = [
                        'product_id' => (int) $productId,
                        'inventory_source_id' => $stockSourceId,
                        'qty' => $defaultQty,
                    ];

                    $channelsRows[] = [
                        'product_id' => (int) $productId,
                        'channel_id' => $channelId,
                    ];

                    $catId = null;
                    $secondary = $info['category_secondary_id'] ?? null;
                    $top = $info['category_top_id'] ?? null;

                    if ($secondary && isset($categoryByTiktok[$secondary])) {
                        $catId = $categoryByTiktok[$secondary];
                    } elseif ($top && isset($categoryByTiktok[$top])) {
                        $catId = $categoryByTiktok[$top];
                    }

                    if ($catId) {
                        $catRows[] = [
                            'product_id' => (int) $productId,
                            'category_id' => $catId,
                        ];
                    }
                }

                if (! empty($pavRows)) {
                    foreach (array_chunk($pavRows, 200) as $piece) {
                        DB::table('product_attribute_values')->insert($piece);
                    }
                }

                if (! empty($flatRows)) {
                    foreach (array_chunk($flatRows, 100) as $piece) {
                        DB::table('product_flat')->upsert(
                            $piece,
                            ['product_id', 'channel', 'locale'],
                            [
                                'sku', 'type', 'product_number', 'name', 'short_description', 'description',
                                'url_key', 'new', 'featured', 'status', 'meta_title', 'meta_keywords',
                                'meta_description', 'price', 'special_price', 'special_price_from',
                                'special_price_to', 'weight', 'attribute_family_id', 'updated_at',
                                'visible_individually',
                            ]
                        );
                    }
                }

                if (! empty($invRows)) {
                    foreach (array_chunk($invRows, 1000) as $piece) {
                        DB::table('product_inventories')->upsert(
                            $piece,
                            ['product_id', 'inventory_source_id'],
                            ['qty']
                        );
                    }
                }

                if (! empty($catRows)) {
                    foreach (array_chunk($catRows, 1000) as $piece) {
                        DB::table('product_categories')->insertOrIgnore($piece);
                    }
                }

                if (! empty($channelsRows)) {
                    foreach (array_chunk($channelsRows, 1000) as $piece) {
                        DB::table('product_channels')->insertOrIgnore($piece);
                    }
                }
            };

            // Retry once on "MySQL server has gone away" by reconnecting fresh.
            $attempt = 0;
            while (true) {
                try {
                    DB::transaction($writer);

                    break;
                } catch (\Throwable $e) {
                    $attempt++;
                    $msg = $e->getMessage();

                    $isConnLost = str_contains($msg, 'gone away')
                        || str_contains($msg, '2006')
                        || str_contains($msg, '2013')
                        || str_contains($msg, 'Lost connection');

                    if ($isConnLost && $attempt < 3) {
                        $this->newLine();
                        $this->warn("  DB connection dropped, reconnecting (attempt {$attempt}) ...");
                        DB::reconnect();

                        continue;
                    }

                    throw $e;
                }
            }

            $bar->advance(count($chunk));
        }

        $bar->finish();
        $this->newLine();

        $this->info('Done.');
    }

    /**
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
