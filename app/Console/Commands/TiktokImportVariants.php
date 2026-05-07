<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionClass;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;

class TiktokImportVariants extends Command
{
    protected $signature = 'tiktok:import-variants
        {--lang=en : API language}
        {--base-url=https://dhgyiu.com/wap/api : TikTok Mall API base}
        {--limit=0 : 0 means all configurable parents}
        {--sleep-ms=60 : Sleep between requests}
        {--progress-file=storage/app/tiktok-mall/variants_progress.json : Progress file path}
        {--reset : Reset progress file before importing}
    ';

    protected $description = 'Import configurable variants using sellerGoods!info.action detail API.';

    private const DETAIL_ENDPOINT = 'sellerGoods!info.action';

    // This must match Webkul\Product\Type\Configurable::$fillableVariantAttributeCodes
    private const FILLABLE_VARIANT_ATTRIBUTE_CODES = [
        'sku',
        'name',
        'url_key',
        'short_description',
        'description',
        'price',
        'weight',
        'status',
        'tax_category_id',
    ];

    public function handle(): int
    {
        $lang = (string) $this->option('lang');
        $baseUrl = (string) $this->option('base-url');
        $limit = (int) $this->option('limit');
        $sleepMs = (int) $this->option('sleep-ms');

        $progressFile = (string) $this->option('progress-file');
        if (! str_starts_with($progressFile, '/')) {
            $progressFile = storage_path(str_replace('storage/', '', $progressFile));
        }

        $reset = (bool) $this->option('reset');
        if ($reset && is_file($progressFile)) {
            unlink($progressFile);
        }

        if (! is_dir(dirname($progressFile))) {
            mkdir(dirname($progressFile), 0775, true);
        }

        $progress = $this->loadProgress($progressFile);
        $processed = $progress['processed'] ?? [];

        $inventorySourceId = (int) (DB::table('inventory_sources')->value('id') ?? 1);
        $channel = core()->getDefaultChannel();
        $channelId = (int) ($channel->id ?? 1);
        $channelCode = (string) ($channel->code ?: 'default');
        $locale = (string) (core()->getDefaultLocaleCodeFromDefaultChannel() ?? app()->getLocale());

        $this->info("Inventory source id: {$inventorySourceId}");
        $this->info("Channel code: {$channelCode}, locale: {$locale}");

        $parentQuery = DB::table('products')
            ->select(['id', 'sku', 'attribute_family_id', 'additional'])
            ->where('type', 'configurable')
            ->where('sku', 'like', 'TG-%');

        if ($limit > 0) {
            $parentQuery->limit($limit);
        }

        $parents = $parentQuery->orderBy('id')->get();
        $this->info('Found parents: '.$parents->count());

        $apiClient = new Client([
            'timeout' => 45,
        ]);

        $attributeRepo = app(AttributeRepository::class);
        $productRepo = app(ProductRepository::class);

        foreach ($parents as $parentRow) {
            $parentId = (int) $parentRow->id;
            $parentSku = (string) $parentRow->sku;

            $additional = is_array($parentRow->additional)
                ? $parentRow->additional
                : json_decode((string) $parentRow->additional, true);

            $sellerGoodsId = $additional['sellerGoodsId']
                ?? $additional['tiktok_seller_goods_id']
                ?? null;

            if (! $sellerGoodsId) {
                $this->warn("Skip parent {$parentId}: missing additional sellerGoodsId");

                continue;
            }

            $sellerGoodsId = (string) $sellerGoodsId;

            if (! empty($processed[$sellerGoodsId])) {
                $this->line("Skip {$sellerGoodsId} (already processed)");

                continue;
            }

            $this->info("Processing parent sellerGoodsId={$sellerGoodsId} (product {$parentId})");

            $detailData = $this->fetchDetail($apiClient, $baseUrl, $sellerGoodsId, $lang);
            if ($detailData === null) {
                $this->warn("Detail API failed for sellerGoodsId={$sellerGoodsId}");
                $this->markProcessed($progressFile, $progress, $processed, $sellerGoodsId, false, 'detail_failed');
                if ($sleepMs > 0) {
                    usleep($sleepMs * 1000);
                }

                continue;
            }

            $variantList = $this->extractVariantList($detailData);
            if (empty($variantList)) {
                $this->warn("No variant list found for sellerGoodsId={$sellerGoodsId}");
                $this->markProcessed($progressFile, $progress, $processed, $sellerGoodsId, false, 'no_variant_list');
                if ($sleepMs > 0) {
                    usleep($sleepMs * 1000);
                }

                continue;
            }

            // Step 1: Create attributes + options, and collect the super attribute codes used by variants.
            $attributeCodeToAttributeId = [];
            $attributeCodeToOptionLabelToOptionId = [];

            $superAttributeCodes = [];
            foreach ($variantList as $variant) {
                $attrSelections = $this->extractAttributeSelections($variant);
                foreach ($attrSelections as $attrName => $attrValueLabel) {
                    if ($attrName === '' || $attrValueLabel === '') {
                        continue;
                    }

                    $attrCode = $this->normalizeAttributeCode((string) $attrName);
                    $superAttributeCodes[$attrCode] = true;

                    if (! isset($attributeCodeToAttributeId[$attrCode])) {
                        $attribute = $attributeRepo->findOneByField('code', $attrCode);
                        if (! $attribute) {
                            // Create a configurable select attribute (options will be filled incrementally).
                            $attribute = $attributeRepo->create([
                                'code' => $attrCode,
                                'admin_name' => (string) $attrName,
                                'type' => 'select',
                                'is_configurable' => 1,
                                'is_required' => 0,
                                'is_unique' => 0,
                                'is_filterable' => 0,
                                'is_comparable' => 0,
                                'is_visible_on_front' => 0,
                                'position' => 0,
                                // No options here; we'll add options as we encounter them.
                            ]);
                        }

                        // Ensure attribute is mapped to the parent's attribute family (default: 1).
                        $familyId = (int) ($parentRow->attribute_family_id ?: 1);
                        $generalGroupId = (int) (DB::table('attribute_groups')
                            ->where('attribute_family_id', $familyId)
                            ->where('name', 'General')
                            ->value('id') ?? 0);

                        if (! $generalGroupId) {
                            $generalGroupId = (int) DB::table('attribute_groups')
                                ->where('attribute_family_id', $familyId)
                                ->orderBy('id')
                                ->value('id');
                        }

                        if ($generalGroupId) {
                            DB::table('attribute_group_mappings')->updateOrInsert([
                                'attribute_id' => $attribute->id,
                                'attribute_group_id' => $generalGroupId,
                            ], [
                                'position' => 1,
                            ]);
                        }

                        $attributeCodeToAttributeId[$attrCode] = (int) $attribute->id;
                    }

                    $label = (string) $attrValueLabel;
                    if (! isset($attributeCodeToOptionLabelToOptionId[$attrCode][$label])) {
                        $optionId = (int) DB::table('attribute_options')
                            ->where('attribute_id', $attributeCodeToAttributeId[$attrCode])
                            ->where('admin_name', $label)
                            ->value('id');

                        if (! $optionId) {
                            // Create option with minimal required fields.
                            $option = DB::table('attribute_options')->insertGetId([
                                'attribute_id' => $attributeCodeToAttributeId[$attrCode],
                                'admin_name' => $label,
                                'swatch_value' => null,
                                'sort_order' => 0,
                            ]);

                            // Note: label translations are optional because UI falls back to admin_name.
                            // If needed later, we can add entries into attribute_option_translations.
                            $optionId = (int) $option;
                        }

                        $attributeCodeToOptionLabelToOptionId[$attrCode][$label] = $optionId;
                    }
                }
            }

            // Step 2: Attach super attributes to the parent product.
            $parentProduct = $productRepo->find($parentId);
            $superAttributeIds = array_values($attributeCodeToAttributeId);
            if (! empty($superAttributeIds)) {
                $parentProduct->super_attributes()->sync($superAttributeIds);
            }

            // Step 3: Prepare configurable type instance (reflection) so createVariant/updateVariant can save values.
            $configurableType = $parentProduct->getTypeInstance();
            $fillableVariantAttributes = $attributeRepo->findWhereIn('code', self::FILLABLE_VARIANT_ATTRIBUTE_CODES);
            foreach ($attributeCodeToAttributeId as $attrCode => $attrId) {
                $attr = $attributeRepo->find((int) $attrId);
                if ($attr) {
                    $fillableVariantAttributes->push($attr);
                }
            }
            $this->setProtectedProperty($configurableType, 'fillableVariantAttributes', $fillableVariantAttributes);

            // Step 4: Create/update variants.
            $variantAttributeCodesSorted = array_keys($superAttributeCodes);
            sort($variantAttributeCodesSorted);

            foreach ($variantList as $variant) {
                $attrSelections = $this->extractAttributeSelections($variant);

                $superAttributes = [];
                foreach ($variantAttributeCodesSorted as $attrCode) {
                    // Find corresponding selection by matching normalized code.
                    $valueLabel = null;
                    foreach ($attrSelections as $attrName => $attrValueLabel) {
                        $candidateCode = $this->normalizeAttributeCode((string) $attrName);
                        if ($candidateCode === $attrCode) {
                            $valueLabel = (string) $attrValueLabel;
                            break;
                        }
                    }

                    if ($valueLabel === null || $valueLabel === '') {
                        continue;
                    }

                    $superAttributes[$attrCode] = $attributeCodeToOptionLabelToOptionId[$attrCode][$valueLabel] ?? null;
                }

                // Skip if we can't build a complete super attribute set.
                if (count($superAttributes) !== count($variantAttributeCodesSorted)) {
                    continue;
                }

                ksort($superAttributes);
                $variantSku = $parentSku.'-variant-'.implode('-', $superAttributes);

                $variantPrice = $this->extractPrice($variant);
                $variantQty = $this->extractQty($variant);

                // Compute variant name from attribute values.
                $labels = [];
                foreach ($superAttributes as $attrCode => $optionId) {
                    $labels[] = $this->findOptionLabelById($attrCode, $optionId, $attributeCodeToOptionLabelToOptionId);
                }
                $variantName = trim($parentSku.' '.implode(' ', array_filter($labels)));

                $inventories = [
                    $inventorySourceId => $variantQty,
                ];

                $variantData = [
                    'sku' => $variantSku,
                    'name' => $variantName,
                    'url_key' => $variantSku,
                    'short_description' => $variantName,
                    'description' => $variantName,
                    'price' => (float) $variantPrice,
                    'weight' => 0,
                    'status' => 1,
                    'tax_category_id' => null,
                    'inventories' => $inventories,
                    'channel' => $channelCode,
                    'locale' => $locale,
                ];

                // Add attribute codes to variant data for updateVariant.
                $variantData = array_merge($variantData, $superAttributes);

                $existingVariantId = (int) DB::table('products')
                    ->where('parent_id', $parentId)
                    ->where('sku', $variantSku)
                    ->value('id');

                if ($existingVariantId > 0) {
                    $configurableType->updateVariant($variantData, $existingVariantId);
                } else {
                    // createVariant expects super attributes as code=>optionId.
                    $configurableType->createVariant($parentProduct, $superAttributes, $variantData);
                }
            }

            $this->markProcessed($progressFile, $progress, $processed, $sellerGoodsId, true, null);

            if ($sleepMs > 0) {
                usleep($sleepMs * 1000);
            }
        }

        $this->info('Variant import finished.');

        return self::SUCCESS;
    }

    private function loadProgress(string $progressFile): array
    {
        if (! is_file($progressFile)) {
            return [
                'processed' => [],
                'updated_at' => null,
            ];
        }

        $content = file_get_contents($progressFile);
        if (! is_string($content) || trim($content) === '') {
            return [
                'processed' => [],
                'updated_at' => null,
            ];
        }

        $json = json_decode($content, true);

        return is_array($json) ? $json : ['processed' => [], 'updated_at' => null];
    }

    private function markProcessed(
        string $progressFile,
        array &$progress,
        array &$processed,
        string $sellerGoodsId,
        bool $success,
        ?string $reason
    ): void {
        $processed[$sellerGoodsId] = $success ? 1 : 0;
        $progress['processed'] = $processed;
        $progress['updated_at'] = now()->toDateTimeString();

        // Keep a small error audit trail for debugging.
        if (! empty($reason) && $success === false) {
            $progress['errors'][$sellerGoodsId] = $reason;
        }

        file_put_contents($progressFile, json_encode($progress, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    private function fetchDetail(Client $client, string $baseUrl, string $sellerGoodsId, string $lang): ?array
    {
        $endpoint = rtrim($baseUrl, '/').'/'.self::DETAIL_ENDPOINT;

        try {
            $response = $client->request('POST', $endpoint, [
                'form_params' => [
                    'sellerGoodsId' => $sellerGoodsId,
                    'lang' => $lang,
                ],
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0',
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);
            if (! is_array($body)) {
                return null;
            }

            $code = $body['code'] ?? null;
            if (! ($code === '0' || $code === 0 || $code === null)) {
                return null;
            }

            $data = $body['data'] ?? null;

            if (is_array($data)) {
                return $data;
            }

            return is_array($body) ? $body : null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function extractVariantList(array $detailData): array
    {
        $keys = [
            'goodsList',
            'productList',
            'goods',
            'variants',
            'variantList',
            'skuList',
            'list',
            'items',
        ];

        foreach ($keys as $key) {
            if (! isset($detailData[$key]) || ! is_array($detailData[$key])) {
                continue;
            }

            $list = $detailData[$key];
            if ($this->isListOfAssociativeArrays($list)) {
                // Quick heuristic: variant items should have some price-like keys.
                $hasPrice = false;
                foreach ($list as $item) {
                    if (! is_array($item)) {
                        continue;
                    }
                    if (isset($item['sellingPrice']) || isset($item['systemPrice']) || isset($item['price'])) {
                        $hasPrice = true;
                        break;
                    }
                }

                if ($hasPrice || count($list) > 1) {
                    return $list;
                }
            }
        }

        // Recursive fallback: find the first list-like array containing price keys.
        $found = $this->findFirstListInTree($detailData, 6);

        return $found ?? [];
    }

    private function isListOfAssociativeArrays(array $list): bool
    {
        if ($list === []) {
            return false;
        }

        $first = null;
        foreach ($list as $v) {
            $first = $v;
            break;
        }

        return is_array($first);
    }

    private function findFirstListInTree(mixed $node, int $depth): ?array
    {
        if ($depth <= 0) {
            return null;
        }

        if (is_array($node)) {
            // If it looks like a list of associative arrays, check for price keys.
            if ($this->isListOfAssociativeArrays($node)) {
                $hasPrice = false;
                foreach ($node as $item) {
                    if (! is_array($item)) {
                        continue;
                    }
                    if (isset($item['sellingPrice']) || isset($item['systemPrice']) || isset($item['price'])) {
                        $hasPrice = true;
                        break;
                    }
                }
                if ($hasPrice) {
                    return $node;
                }
            }

            foreach ($node as $v) {
                $res = $this->findFirstListInTree($v, $depth - 1);
                if ($res) {
                    return $res;
                }
            }
        }

        return null;
    }

    private function extractAttributeSelections(array $variant): array
    {
        $candidates = [];

        // Common: attributes as associative map.
        if (isset($variant['attributes']) && is_array($variant['attributes'])) {
            $candidates[] = $variant['attributes'];
        }

        // Alternative shapes.
        if (isset($variant['attributeValues']) && is_array($variant['attributeValues'])) {
            $candidates[] = $variant['attributeValues'];
        }

        foreach ($candidates as $cand) {
            if ($this->isAssoc($cand)) {
                // name => valueLabel
                return $cand;
            }

            // name/value objects.
            $out = [];
            foreach ($cand as $row) {
                if (! is_array($row)) {
                    continue;
                }

                $name = $row['name'] ?? $row['attributeName'] ?? $row['key'] ?? null;
                $value = $row['value'] ?? $row['label'] ?? $row['selected'] ?? null;
                if ($name !== null && $value !== null) {
                    $out[(string) $name] = (string) $value;
                }
            }

            if (! empty($out)) {
                return $out;
            }
        }

        return [];
    }

    private function isAssoc(array $arr): bool
    {
        $keys = array_keys($arr);

        return $keys !== range(0, count($arr) - 1);
    }

    private function extractPrice(array $variant): float
    {
        $selling = $variant['sellingPrice'] ?? $variant['selling_price'] ?? null;
        $system = $variant['systemPrice'] ?? $variant['system_price'] ?? null;
        $price = $variant['price'] ?? null;

        foreach ([$selling, $system, $price] as $v) {
            if ($v !== null && $v !== '') {
                return (float) $v;
            }
        }

        return 0.0;
    }

    private function extractQty(array $variant): int
    {
        foreach (['stock', 'qty', 'quantity', 'stockQty'] as $k) {
            if (isset($variant[$k]) && $variant[$k] !== '') {
                return (int) $variant[$k];
            }
        }

        // If API doesn't provide inventory per variant, keep it saleable.
        return 999;
    }

    private function normalizeAttributeCode(string $attributeName): string
    {
        $code = Str::slug(mb_strtolower(trim($attributeName)), '_');
        $code = str_replace('-', '_', $code);
        $code = preg_replace('/[^a-z0-9_]/i', '', (string) $code);

        return $code ?: 'attr_'.Str::random(6);
    }

    private function setProtectedProperty(object $object, string $property, mixed $value): void
    {
        $ref = new ReflectionClass($object);
        if (! $ref->hasProperty($property)) {
            return;
        }

        $prop = $ref->getProperty($property);
        $prop->setAccessible(true);
        $prop->setValue($object, $value);
    }

    private function findOptionLabelById(
        string $attrCode,
        int $optionId,
        array $attributeCodeToOptionLabelToOptionId
    ): string {
        foreach ($attributeCodeToOptionLabelToOptionId[$attrCode] ?? [] as $label => $oid) {
            if ((int) $oid === $optionId) {
                return (string) $label;
            }
        }

        return '';
    }
}
