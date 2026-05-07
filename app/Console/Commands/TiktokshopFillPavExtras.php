<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Fast SQL-only back-fill for the secondary PAV fields the edit blade reads
 * but the main enrich step did not write:
 *
 *   - sku                (mirrors products.sku)
 *   - product_number     (mirrors products.sku)
 *   - meta_title         (mirrors existing PAV.name)
 *   - short_description  (first ~500 chars of existing PAV.description)
 *
 * Targets only products that already have a populated PAV.name, leaving truly
 * empty placeholders alone. Idempotent: existing rows for the targeted attribute
 * codes are deleted then re-inserted.
 */
class TiktokshopFillPavExtras extends Command
{
    protected $signature = 'tiktokshop:fill-pav-extras
        {--dry-run : Print counts without writing}';

    protected $description = 'SQL-only back-fill of sku/meta_title/product_number/short_description PAV rows from existing data.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $codes = ['sku', 'product_number', 'meta_title', 'short_description', 'name', 'description'];

        $attrs = DB::table('attributes')->whereIn('code', $codes)->get()->keyBy('code');

        foreach (['sku', 'product_number', 'meta_title', 'short_description'] as $code) {
            if (! isset($attrs[$code])) {
                $this->warn("attribute '{$code}' not found, skipping.");
            }
        }

        $nameAttrId = isset($attrs['name']) ? (int) $attrs['name']->id : null;
        $descAttrId = isset($attrs['description']) ? (int) $attrs['description']->id : null;

        $populatedProductIds = DB::table('product_attribute_values')
            ->where('attribute_id', $nameAttrId)
            ->whereNotNull('text_value')
            ->where('text_value', '<>', '')
            ->pluck('product_id')
            ->unique()
            ->values();

        $this->info('Products with populated PAV.name: '.$populatedProductIds->count());

        if ($populatedProductIds->isEmpty()) {
            $this->warn('Nothing to fill.');

            return self::SUCCESS;
        }

        if (isset($attrs['sku'])) {
            $this->fill('sku', (int) $attrs['sku']->id, $populatedProductIds, $dryRun, function ($pids) {
                /** Mirror products.sku into PAV. */
                return DB::table('products')
                    ->whereIn('id', $pids)
                    ->whereNotNull('sku')
                    ->where('sku', '<>', '')
                    ->select('id as product_id', DB::raw('sku as text_value'))
                    ->get()
                    ->map(fn ($r) => ['product_id' => (int) $r->product_id, 'text_value' => (string) $r->text_value]);
            }, $attrs['sku']);
        }

        if (isset($attrs['product_number'])) {
            $this->fill('product_number', (int) $attrs['product_number']->id, $populatedProductIds, $dryRun, function ($pids) {
                return DB::table('products')
                    ->whereIn('id', $pids)
                    ->whereNotNull('sku')
                    ->where('sku', '<>', '')
                    ->select('id as product_id', DB::raw('sku as text_value'))
                    ->get()
                    ->map(fn ($r) => ['product_id' => (int) $r->product_id, 'text_value' => (string) $r->text_value]);
            }, $attrs['product_number']);
        }

        if (isset($attrs['meta_title']) && $nameAttrId) {
            $this->fill('meta_title', (int) $attrs['meta_title']->id, $populatedProductIds, $dryRun, function ($pids) use ($nameAttrId) {
                /**
                 * Pull current name PAV per product. We grab one row per product;
                 * if the name is per-locale we just keep the locale present.
                 */
                return DB::table('product_attribute_values')
                    ->whereIn('product_id', $pids)
                    ->where('attribute_id', $nameAttrId)
                    ->whereNotNull('text_value')
                    ->where('text_value', '<>', '')
                    ->select('product_id', 'text_value')
                    ->get()
                    ->groupBy('product_id')
                    ->map(fn ($g) => [
                        'product_id' => (int) $g->first()->product_id,
                        'text_value' => mb_substr((string) $g->first()->text_value, 0, 250),
                    ])
                    ->values();
            }, $attrs['meta_title']);
        }

        if (isset($attrs['short_description']) && $descAttrId) {
            $this->fill('short_description', (int) $attrs['short_description']->id, $populatedProductIds, $dryRun, function ($pids) use ($descAttrId) {
                return DB::table('product_attribute_values')
                    ->whereIn('product_id', $pids)
                    ->where('attribute_id', $descAttrId)
                    ->whereNotNull('text_value')
                    ->where('text_value', '<>', '')
                    ->select('product_id', 'text_value')
                    ->get()
                    ->groupBy('product_id')
                    ->map(function ($g) {
                        $raw = (string) $g->first()->text_value;
                        $stripped = trim(strip_tags($raw));
                        $shortened = mb_substr($stripped, 0, 500);

                        return [
                            'product_id' => (int) $g->first()->product_id,
                            'text_value' => $shortened !== '' ? $shortened : null,
                        ];
                    })
                    ->filter(fn ($r) => $r['text_value'] !== null && $r['text_value'] !== '')
                    ->values();
            }, $attrs['short_description']);
        }

        $this->info('Done.');

        return self::SUCCESS;
    }

    protected function fill(string $code, int $attrId, $productIds, bool $dryRun, \Closure $rowsBuilder, $attribute): void
    {
        $valuePerChannel = (bool) ($attribute->value_per_channel ?? false);
        $valuePerLocale = (bool) ($attribute->value_per_locale ?? false);
        $channelCol = $valuePerChannel ? (string) (core()->getDefaultChannelCode()) : null;
        $localeCol = $valuePerLocale ? (string) (core()->getDefaultLocaleCodeFromDefaultChannel()) : null;

        $totalDeleted = 0;
        $totalInserted = 0;

        foreach (array_chunk($productIds->all(), 2000) as $chunk) {
            $rows = $rowsBuilder($chunk);

            if ($rows->isEmpty()) {
                continue;
            }

            if ($dryRun) {
                $totalInserted += $rows->count();

                continue;
            }

            $insert = [];

            foreach ($rows as $r) {
                $insert[] = [
                    'product_id' => $r['product_id'],
                    'attribute_id' => $attrId,
                    'channel' => $channelCol,
                    'locale' => $localeCol,
                    'text_value' => $r['text_value'],
                    'boolean_value' => null,
                    'integer_value' => null,
                    'float_value' => null,
                    'datetime_value' => null,
                    'date_value' => null,
                    'json_value' => null,
                ];
            }

            DB::transaction(function () use (&$totalDeleted, &$totalInserted, $insert, $attrId) {
                $pids = array_unique(array_column($insert, 'product_id'));

                $totalDeleted += DB::table('product_attribute_values')
                    ->whereIn('product_id', $pids)
                    ->where('attribute_id', $attrId)
                    ->delete();

                foreach (array_chunk($insert, 1000) as $piece) {
                    DB::table('product_attribute_values')->insert($piece);
                    $totalInserted += count($piece);
                }
            });
        }

        $this->info(sprintf(
            "  %s : deleted=%s inserted=%s%s",
            str_pad($code, 18),
            number_format($totalDeleted),
            number_format($totalInserted),
            $dryRun ? ' (dry-run)' : ''
        ));
    }
}
