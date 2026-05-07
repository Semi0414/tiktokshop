<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\Category\Repositories\CategoryRepository;

class TiktokImportCatalog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tiktok:import-catalog
        {--csv=exports/dhgyiu_all_products_flattened.csv : Path to the TikTok Mall CSV}
        {--limit=0 : 0 means all rows}
        {--offset=0 : Number of data rows to skip before importing}
        {--dry-run : Do not write DB changes}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import TikTok Mall CSV into Bagisto categories + parent configurable products.';

    public function handle(): int
    {
        $csvPath = (string) $this->option('csv');
        $limit = (int) $this->option('limit');
        $offset = max(0, (int) $this->option('offset'));
        $dryRun = (bool) $this->option('dry-run');

        if (! str_starts_with($csvPath, '/')) {
            $csvPath = base_path($csvPath);
        }

        if (! is_file($csvPath)) {
            $this->error("CSV not found: {$csvPath}");

            return self::FAILURE;
        }

        $channel = core()->getDefaultChannel();
        $locale = core()->getDefaultLocaleCodeFromDefaultChannel() ?? app()->getLocale();

        $rootCategoryId = (int) $channel->root_category_id;
        $channelId = (int) $channel->id;
        $channelCode = (string) ($channel->code ?: 'default');

        $this->info("Import starting: {$csvPath}");
        $this->info("Row window: offset={$offset}, limit={$limit}");
        $this->info("Channel: id={$channelId}, code={$channelCode}, locale={$locale}");
        $this->info("Root category id: {$rootCategoryId}");

        $categoryRepository = app(CategoryRepository::class);

        // ---- First pass: collect categories + products (minimal fields) ----
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

        $topCategories = []; // [tiktokCategoryId => ['name'=>..., 'slug'=>...]]
        $subCategories = []; // [tiktokSubCategoryId => ['name'=>..., 'slug'=>..., 'parent'=>topId]]

        $products = []; // keyed by parent key; each: sku,name,description,url_key,price,status,tiktokSellerGoodsId,parentGoodsId,categoryKey(top/sub)

        $rowNum = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            if ($offset > 0 && $rowNum <= $offset) {
                continue;
            }

            if ($row === [null] || $row === false) {
                continue;
            }

            /**
             * Some rows may have a different number of columns than the header
             * (e.g. malformed CSV lines). We avoid crashing by mapping by index:
             * - extra columns are ignored
             * - missing columns are filled with null
             */
            $assoc = [];
            $headerCount = count($header);
            $rowCount = count($row);
            for ($i = 0; $i < $headerCount; $i++) {
                $assoc[$header[$i]] = $row[$i] ?? null;
            }

            $productRowId = trim((string) ($assoc['id'] ?? ''));
            if ($productRowId === '') {
                continue;
            }

            $categoryId = trim((string) ($assoc['categoryId'] ?? ''));
            $categoryName = trim((string) ($assoc['categoryName'] ?? ''));
            $secondaryCategoryId = trim((string) ($assoc['secondaryCategoryId'] ?? ''));
            $secondaryCategoryName = trim((string) ($assoc['secondaryCateName'] ?? ''));

            // Top category.
            if ($categoryId !== '') {
                $slug = 'tiktok-cat-'.$categoryId;
                $topCategories[$categoryId] ??= [
                    'name' => $categoryName !== '' ? $categoryName : 'Category '.$categoryId,
                    'slug' => $slug,
                ];
                // Prefer a non-empty name if we see one later.
                if ($categoryName !== '' && ($topCategories[$categoryId]['name'] ?? '') === 'Category '.$categoryId) {
                    $topCategories[$categoryId]['name'] = $categoryName;
                }
            }

            // Secondary category.
            $isSecondaryValid = $secondaryCategoryId !== '' && $secondaryCategoryId !== '0';
            $categoryKey = $categoryId;
            $categoryDbRef = null;

            if ($isSecondaryValid) {
                $subSlug = 'tiktok-cat-'.$secondaryCategoryId;
                $subCategories[$secondaryCategoryId] ??= [
                    'name' => $secondaryCategoryName !== '' ? $secondaryCategoryName : 'Category '.$secondaryCategoryId,
                    'slug' => $subSlug,
                    'parent' => $categoryId,
                ];

                if ($secondaryCategoryName !== '' && ($subCategories[$secondaryCategoryId]['name'] ?? '') === 'Category '.$secondaryCategoryId) {
                    $subCategories[$secondaryCategoryId]['name'] = $secondaryCategoryName;
                }

                $categoryKey = $secondaryCategoryId; // product maps to secondary category
            }

            $name = trim((string) ($assoc['name'] ?? ''));
            if ($name === '') {
                $name = 'Product '.$productRowId;
            }

            $sellingPrice = $assoc['sellingPrice'] ?? ($assoc['systemPrice'] ?? null);
            $sellingPrice = $sellingPrice !== null && $sellingPrice !== '' ? (float) $sellingPrice : null;

            $systemPrice = $assoc['systemPrice'] ?? null;
            $systemPrice = $systemPrice !== null && $systemPrice !== '' ? (float) $systemPrice : null;

            $price = $sellingPrice ?? $systemPrice ?? 0.0;

            $status = (int) (($assoc['isShelf'] ?? 1) ?: 1);

            $description = (string) ($assoc['des'] ?? '');

            // CSV 'id' is sellerGoodsId while goodsId is parent-level product key.
            $sellerGoodsId = $productRowId;
            $parentGoodsId = trim((string) ($assoc['goodsId'] ?? ''));
            $productGroupKey = $parentGoodsId !== '' ? $parentGoodsId : $sellerGoodsId;

            $sku = 'TG-'.$productGroupKey;
            $urlKeyBase = Str::slug($name);
            $urlKey = ($urlKeyBase !== '' ? $urlKeyBase : 'product-'.$productGroupKey).'-'.$productGroupKey;

            if (! isset($products[$productGroupKey])) {
                if ($limit > 0 && count($products) >= $limit) {
                    break;
                }

                $products[$productGroupKey] = [
                    'tiktok_seller_goods_id' => $sellerGoodsId,
                    'tiktok_parent_goods_id' => $productGroupKey,
                    'sku' => $sku,
                    'type' => 'configurable',
                    'name' => $name,
                    'description' => $description,
                    'url_key' => $urlKey,
                    'price' => $price,
                    'status' => $status,
                    'category_key' => $categoryKey, // tiktok category id or secondary id
                ];
            } else {
                // Merge variant-like rows into one parent product entry.
                $products[$productGroupKey]['price'] = min((float) $products[$productGroupKey]['price'], (float) $price);
                $products[$productGroupKey]['status'] = (int) $products[$productGroupKey]['status'] || (int) $status ? 1 : 0;

                if (
                    (! $products[$productGroupKey]['description'] || trim((string) $products[$productGroupKey]['description']) === '')
                    && trim($description) !== ''
                ) {
                    $products[$productGroupKey]['description'] = $description;
                }

                if (
                    (
                        ! $products[$productGroupKey]['name']
                        || str_starts_with((string) $products[$productGroupKey]['name'], 'Product ')
                    )
                    && $name !== ''
                ) {
                    $products[$productGroupKey]['name'] = $name;
                }
            }
        }

        fclose($handle);

        $products = array_values($products);

        $this->info('Collected: topCategories='.count($topCategories).', subCategories='.count($subCategories).', products='.count($products));

        if (empty($products)) {
            $this->warn('No products found in CSV (after filtering).');

            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->warn('Dry-run enabled. No database changes will be written.');

            return self::SUCCESS;
        }

        // ---- Create categories (top then secondary) ----
        $now = now();
        $position = 0;
        $topDbIdsByTiktokId = [];

        foreach ($topCategories as $tiktokId => $cat) {
            $existing = $categoryRepository->findBySlug($cat['slug']);
            if ($existing) {
                $topDbIdsByTiktokId[$tiktokId] = $existing->id;

                continue;
            }

            $created = $categoryRepository->create([
                'locale' => 'all',
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'description' => $cat['name'],
                'status' => 1,
                'parent_id' => $rootCategoryId,
                'position' => $position,
                'additional' => json_encode([
                    'tiktok_category_id' => $tiktokId,
                ], JSON_UNESCAPED_UNICODE),
            ]);

            $topDbIdsByTiktokId[$tiktokId] = $created->id;
            $position++;
        }

        $secondaryDbIdsByTiktokId = [];
        $secondaryPosition = 0;
        foreach ($subCategories as $tiktokId => $cat) {
            $existing = $categoryRepository->findBySlug($cat['slug']);
            if ($existing) {
                $secondaryDbIdsByTiktokId[$tiktokId] = $existing->id;

                continue;
            }

            $parentTiktok = $cat['parent'] ?? null;
            $parentDbId = $parentTiktok && isset($topDbIdsByTiktokId[$parentTiktok])
                ? $topDbIdsByTiktokId[$parentTiktok]
                : $rootCategoryId;

            $created = $categoryRepository->create([
                'locale' => 'all',
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'description' => $cat['name'],
                'status' => 1,
                'parent_id' => $parentDbId,
                'position' => $secondaryPosition,
                'additional' => json_encode([
                    'tiktok_category_id' => $tiktokId,
                ], JSON_UNESCAPED_UNICODE),
            ]);

            $secondaryDbIdsByTiktokId[$tiktokId] = $created->id;
            $secondaryPosition++;
        }

        // ---- Upsert parent products ----
        $attributeFamilyId = 1;

        $productSkus = [];
        foreach ($products as $p) {
            $productSkus[] = $p['sku'];
        }

        // Prepare upsert payload for products table.
        $productRows = [];
        $nowString = $now->toDateTimeString();
        foreach ($products as $p) {
            $productRows[] = [
                'sku' => $p['sku'],
                'type' => $p['type'],
                'parent_id' => null,
                'attribute_family_id' => $attributeFamilyId,
                'additional' => json_encode([
                    'sellerGoodsId' => $p['tiktok_seller_goods_id'],
                    'parentGoodsId' => $p['tiktok_parent_goods_id'],
                ], JSON_UNESCAPED_UNICODE),
                'created_at' => $nowString,
                'updated_at' => $nowString,
            ];
        }

        // Chunked upsert avoids a single massive query stalling/locking the DB.
        // This is especially important for larger CSVs.
        $upsertChunkSize = 5000;
        $totalProductRows = count($productRows);
        $numChunks = (int) ceil($totalProductRows / $upsertChunkSize);
        for ($chunkIndex = 0; $chunkIndex < $numChunks; $chunkIndex++) {
            $chunkOffset = $chunkIndex * $upsertChunkSize;
            $chunk = array_slice($productRows, $chunkOffset, $upsertChunkSize);

            if (empty($chunk)) {
                continue;
            }

            $this->info('Upserting products chunk '.($chunkIndex + 1)."/{$numChunks} (".count($chunk).' rows)...');

            DB::table('products')->upsert(
                $chunk,
                ['sku'],
                ['type', 'attribute_family_id', 'parent_id', 'additional', 'updated_at']
            );
        }

        // Fetch product ids by sku.
        $productIdBySku = [];
        foreach (array_chunk($productSkus, 2000) as $skuChunk) {
            $rows = DB::table('products')
                ->select('id', 'sku')
                ->whereIn('sku', $skuChunk)
                ->get();

            foreach ($rows as $row) {
                $productIdBySku[(string) $row->sku] = (int) $row->id;
            }
        }

        // ---- Upsert product_flat ----
        $productFlatRows = [];
        $productChannelsRows = [];
        $productCategoriesRows = [];

        $flatNow = $nowString;
        foreach ($products as $p) {
            $productId = $productIdBySku[$p['sku']] ?? null;
            if (! $productId) {
                continue;
            }

            $mappedCategoryId = null;
            if (isset($secondaryDbIdsByTiktokId[$p['category_key']])) {
                $mappedCategoryId = $secondaryDbIdsByTiktokId[$p['category_key']];
            } elseif (isset($topDbIdsByTiktokId[$p['category_key']])) {
                $mappedCategoryId = $topDbIdsByTiktokId[$p['category_key']];
            }

            // If category mapping is missing, skip inserting the product into catalog joins.
            if (! $mappedCategoryId) {
                continue;
            }

            $productFlatRows[] = [
                'sku' => $p['sku'],
                'type' => $p['type'],
                'product_number' => '',
                'name' => $p['name'],
                'short_description' => null,
                'description' => $p['description'],
                'url_key' => $p['url_key'],
                'new' => 0,
                'featured' => 0,
                'status' => $p['status'],
                'meta_title' => $p['name'],
                'meta_keywords' => null,
                'meta_description' => null,
                'price' => (float) $p['price'],
                'special_price' => null,
                'special_price_from' => null,
                'special_price_to' => null,
                'weight' => null,
                'created_at' => $flatNow,
                'locale' => $locale,
                'channel' => $channelCode,
                'attribute_family_id' => $attributeFamilyId,
                'product_id' => $productId,
                'updated_at' => $flatNow,
                'parent_id' => null,
                'visible_individually' => 1,
            ];

            $productChannelsRows[] = [
                'product_id' => $productId,
                'channel_id' => $channelId,
            ];

            $productCategoriesRows[] = [
                'product_id' => $productId,
                'category_id' => $mappedCategoryId,
            ];
        }

        $this->info('Writing product_flat, product_channels, product_categories...');

        // Chunk product_flat upsert to reduce the chance of a single huge SQL statement
        // taking too long / timing out under load.
        $productFlatUpsertChunkSize = 5000;
        $totalProductFlatRows = count($productFlatRows);
        $numProductFlatChunks = (int) ceil($totalProductFlatRows / $productFlatUpsertChunkSize);
        for ($chunkIndex = 0; $chunkIndex < $numProductFlatChunks; $chunkIndex++) {
            $chunkOffset = $chunkIndex * $productFlatUpsertChunkSize;
            $chunk = array_slice($productFlatRows, $chunkOffset, $productFlatUpsertChunkSize);

            if (empty($chunk)) {
                continue;
            }

            $this->info('Upserting product_flat chunk '.($chunkIndex + 1)."/{$numProductFlatChunks} (".count($chunk).' rows)...');

            DB::table('product_flat')->upsert(
                $chunk,
                ['product_id', 'channel', 'locale'],
                [
                    'sku',
                    'type',
                    'product_number',
                    'name',
                    'short_description',
                    'description',
                    'url_key',
                    'new',
                    'featured',
                    'status',
                    'meta_title',
                    'meta_keywords',
                    'meta_description',
                    'price',
                    'special_price',
                    'special_price_from',
                    'special_price_to',
                    'weight',
                    'attribute_family_id',
                    'updated_at',
                    'visible_individually',
                ]
            );
        }

        // Upsert product channels.
        foreach (array_chunk($productChannelsRows, 2000) as $chunk) {
            // `product_channels` has a unique index on (product_id, channel_id) so we can safely ignore duplicates.
            DB::table('product_channels')->insertOrIgnore($chunk);
        }

        // Replace product-category mappings to avoid duplicates on reruns.
        $productIds = array_values(array_unique(array_map(static fn ($r) => $r['product_id'], $productCategoriesRows)));
        foreach (array_chunk($productIds, 1000) as $pidChunk) {
            DB::table('product_categories')->whereIn('product_id', $pidChunk)->delete();
        }
        foreach (array_chunk($productCategoriesRows, 2000) as $chunk) {
            DB::table('product_categories')->insert($chunk);
        }

        /**
         * Important: Bagisto storefront queries products via `product_attribute_values`,
         * not `product_flat`. So we must also populate attribute values for:
         * - name
         * - url_key
         * - description
         * - status
         * - visible_individually
         * (and we also add `new` + `featured` for completeness).
         */
        $this->info('Writing product_attribute_values (storefront requirements)...');

        $requiredAttributeCodes = [
            'name',
            'url_key',
            'description',
            'price',
            'status',
            'visible_individually',
            'new',
            'featured',
        ];

        $attributes = DB::table('attributes')
            ->whereIn('code', $requiredAttributeCodes)
            ->get()
            ->keyBy('code');

        $missingCodes = array_values(array_diff($requiredAttributeCodes, array_keys($attributes->toArray())));
        if (! empty($missingCodes)) {
            $this->warn('Missing attribute definitions in DB: '.implode(',', $missingCodes));
        }

        $attributeTypeToColumn = [
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

        $productAttributeProductIds = array_values(array_unique(array_map(static fn ($r) => (int) $r['product_id'], $productFlatRows)));
        $productAttributeProductIdsSet = array_fill_keys($productAttributeProductIds, 1);

        $attributeIds = array_values(array_map(static fn ($a) => (int) $a->id, $attributes->all()));

        if (! empty($attributeIds) && ! empty($productAttributeProductIds)) {
            // Remove old values for those products + attributes (safe for reruns).
            foreach (array_chunk($productAttributeProductIds, 2000) as $pidChunk) {
                DB::table('product_attribute_values')
                    ->whereIn('product_id', $pidChunk)
                    ->whereIn('attribute_id', $attributeIds)
                    ->delete();
            }

            $rowsToInsert = [];
            $chunkSize = 5000;

            foreach ($products as $p) {
                $productId = $productIdBySku[$p['sku']] ?? null;
                if (! $productId) {
                    continue;
                }

                if (! isset($productAttributeProductIdsSet[(int) $productId])) {
                    continue; // this product didn't get mapped to a category
                }

                $values = [
                    'name' => $p['name'],
                    'url_key' => $p['url_key'],
                    'description' => $p['description'],
                    'price' => (float) ($p['price'] ?? 0.0),
                    'status' => (bool) ((int) $p['status'] === 1),
                    'visible_individually' => true,
                    'new' => false,
                    'featured' => false,
                ];

                foreach ($requiredAttributeCodes as $code) {
                    if (! isset($attributes[$code])) {
                        continue;
                    }

                    $attr = $attributes[$code];
                    $col = $attributeTypeToColumn[$attr->type] ?? null;
                    if (! $col) {
                        continue;
                    }

                    $channel = $attr->value_per_channel ? $channelCode : null;
                    $localeValue = $attr->value_per_locale ? $locale : null;

                    $row = [
                        'product_id' => (int) $productId,
                        'attribute_id' => (int) $attr->id,
                        'channel' => $channel,
                        'locale' => $localeValue,
                        $col => $values[$code] ?? null,
                    ];

                    // Avoid inserting both text_value and boolean_value etc by nulling others.
                    foreach (['text_value', 'boolean_value', 'integer_value', 'float_value', 'datetime_value', 'date_value', 'json_value'] as $maybeCol) {
                        if ($maybeCol !== $col) {
                            $row[$maybeCol] = null;
                        }
                    }

                    $rowsToInsert[] = $row;

                    if (count($rowsToInsert) >= $chunkSize) {
                        DB::table('product_attribute_values')->insert($rowsToInsert);
                        $rowsToInsert = [];
                    }
                }
            }

            if (! empty($rowsToInsert)) {
                DB::table('product_attribute_values')->insert($rowsToInsert);
            }
        }

        $this->info('Import finished.');

        return self::SUCCESS;
    }
}
