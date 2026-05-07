<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TiktokNormalizeDuplicateParents extends Command
{
    protected $signature = 'tiktok:normalize-duplicate-parents
        {--csv=exports/dhgyiu_all_products_flattened.csv : Path to source CSV}
        {--limit=0 : Limit rows to scan}
        {--offset=0 : Number of data rows to skip}
    ';

    protected $description = 'Group TG products by goodsId and hide duplicate variant-like parent cards from listing.';

    public function handle(): int
    {
        $csvPath = (string) $this->option('csv');
        $limit = (int) $this->option('limit');
        $offset = max(0, (int) $this->option('offset'));

        if (! str_starts_with($csvPath, '/')) {
            $csvPath = base_path($csvPath);
        }

        if (! is_file($csvPath)) {
            $this->error("CSV not found: {$csvPath}");

            return self::FAILURE;
        }

        $skuToProductId = DB::table('products')
            ->where('sku', 'like', 'TG-%')
            ->pluck('id', 'sku')
            ->mapWithKeys(fn ($id, $sku) => [(string) $sku => (int) $id])
            ->toArray();

        if (empty($skuToProductId)) {
            $this->warn('No TG products found.');

            return self::SUCCESS;
        }

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

        $groups = [];
        $rowNum = 0;
        $rowsSeen = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            if ($offset > 0 && $rowNum <= $offset) {
                continue;
            }

            if ($limit > 0 && $rowsSeen >= $limit) {
                break;
            }

            $rowsSeen++;

            $assoc = [];
            $headerCount = count($header);
            for ($i = 0; $i < $headerCount; $i++) {
                $assoc[$header[$i]] = $row[$i] ?? null;
            }

            $sellerGoodsId = trim((string) ($assoc['id'] ?? ''));
            $goodsId = trim((string) ($assoc['goodsId'] ?? ''));

            if ($sellerGoodsId === '' || $goodsId === '') {
                continue;
            }

            $sku = 'TG-'.$sellerGoodsId;
            $productId = $skuToProductId[$sku] ?? null;

            if (! $productId) {
                continue;
            }

            $groups[$goodsId] ??= [];
            $groups[$goodsId][$productId] = true;
        }

        fclose($handle);

        $visibleAttributeId = (int) (DB::table('attributes')->where('code', 'visible_individually')->value('id') ?? 0);

        $groupsProcessed = 0;
        $childrenHidden = 0;

        foreach ($groups as $goodsId => $productIdsMap) {
            $productIds = array_map('intval', array_keys($productIdsMap));

            if (count($productIds) <= 1) {
                continue;
            }

            sort($productIds);
            $parentId = $productIds[0];
            $childIds = array_slice($productIds, 1);

            DB::table('products')->where('id', $parentId)->update([
                'type' => 'configurable',
                'parent_id' => null,
            ]);

            DB::table('products')
                ->whereIn('id', $childIds)
                ->update([
                    'type' => 'simple',
                    'parent_id' => $parentId,
                ]);

            DB::table('product_flat')
                ->whereIn('product_id', $childIds)
                ->update(['visible_individually' => 0]);

            if ($visibleAttributeId) {
                DB::table('product_attribute_values')
                    ->where('attribute_id', $visibleAttributeId)
                    ->whereIn('product_id', $childIds)
                    ->update(['boolean_value' => 0]);
            }

            $groupsProcessed++;
            $childrenHidden += count($childIds);
        }

        $this->info('Normalization finished.');
        $this->line('rows_seen: '.$rowsSeen);
        $this->line('groups_processed: '.$groupsProcessed);
        $this->line('children_hidden: '.$childrenHidden);

        return self::SUCCESS;
    }
}
