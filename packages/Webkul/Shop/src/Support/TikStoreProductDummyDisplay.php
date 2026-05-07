<?php

namespace Webkul\Shop\Support;

use Illuminate\Support\Facades\DB;
use Webkul\Product\Contracts\Product;

/**
 * Deterministic TikStore-style mock stats (rating, sold, tags, badges) for listing + PDP.
 */
class TikStoreProductDummyDisplay
{
    /**
     * Build dummy display data from a catalog product (uses product_flat for real effective price).
     */
    public function fromProduct(Product $product): object
    {
        $flat = $this->getProductFlatRow((int) $product->id);

        $row = (object) [
            'product_id' => (int) $product->id,
        ];

        if ($flat) {
            $row->price = $flat->price;
            $row->special_price = $flat->special_price;
            $row->special_price_from = $flat->special_price_from ?? null;
            $row->special_price_to = $flat->special_price_to ?? null;
            $row->final_price = $this->resolveFinalPrice($flat);
        } else {
            $row->final_price = 0.0;
        }

        return $this->enrich($row);
    }

    /**
     * Enrich a product_flat row (must set final_price before call, or pass price fields).
     *
     * @param  object{product_id:int, final_price?:float, price?:mixed, special_price?:mixed}  $row
     */
    public function enrich(object $row): object
    {
        $id = (int) ($row->product_id ?? 0);
        $seed = crc32((string) $id);
        $mod15 = $id % 15;

        $row->tik_rating = round(3.6 + ($mod15 / 10.0), 1);

        $row->tik_reviews_total = 400 + ($seed % 2200);
        $row->tik_sold_total = 300 + ($seed % 5200);
        $row->tik_reviews_fmt = $this->formatCompactNumber($row->tik_reviews_total);
        $row->tik_sold_fmt = $this->formatCompactNumber($row->tik_sold_total);

        $tagPool = [
            'Unisex', 'Limited drop', 'Electronics', 'Noise canceling', 'Creator gear', 'Studio',
            'Desk setup', 'Soft touch', 'Fashion', 'Lifestyle', 'Accessories', 'Creator merch',
            'Creator pick', 'Bestseller', 'New', 'Official store', 'Bundles', 'Trending',
        ];
        $t1 = $tagPool[$seed % count($tagPool)];
        $t2 = $tagPool[($seed >> 5) % count($tagPool)];
        if ($t1 === $t2) {
            $t2 = $tagPool[($seed >> 5 ^ 3) % count($tagPool)];
        }
        $row->tik_tags = [$t1, $t2];

        $shipMod = $id % 3;
        if ($shipMod === 0) {
            $row->tik_ship_label = __('Free shipping');
        } elseif ($shipMod === 1) {
            $row->tik_ship_label = __('24h dispatch');
        } else {
            $row->tik_ship_label = __('Ships in 2 days');
        }

        $pay = (float) ($row->final_price ?? 0);
        if ($pay > 0) {
            $pctOptions = [8, 10, 11, 12, 15, 18, 20, 22, 25, 28, 30, 32, 35];
            $pct = $pctOptions[$seed % count($pctOptions)];
            $row->tik_discount_pct_display = $pct;
            $row->tik_compare_at_price = round($pay / (1 - ($pct / 100.0)), 4);

            $textBadges = [
                ['text' => 'Creator pick', 'dot' => true],
                ['text' => 'Bestseller', 'dot' => true],
                ['text' => 'New', 'dot' => true],
                ['text' => 'Limited', 'dot' => true],
                ['text' => 'Creator drop', 'dot' => true],
                ['text' => 'Hot now', 'dot' => true],
                ['text' => 'Official merch', 'dot' => true],
                ['text' => 'Flash deal', 'dot' => true],
            ];

            $usePercentImageBadge = ($seed % 100) < 55;

            if ($usePercentImageBadge) {
                $row->tik_badge_is_percent = true;
                $row->tik_badge_text = $pct.'% OFF';
                $row->tik_badge_dot = true;
            } else {
                $tb = $textBadges[($seed >> 7) % count($textBadges)];
                $row->tik_badge_is_percent = false;
                $row->tik_badge_text = $tb['text'];
                $row->tik_badge_dot = $tb['dot'];
            }
        } else {
            $row->tik_compare_at_price = 0.0;
            $row->tik_discount_pct_display = 0;
            $row->tik_badge_text = '';
            $row->tik_badge_dot = false;
            $row->tik_badge_is_percent = false;
        }

        return $row;
    }

    public function resolveFinalPrice(object $row): float
    {
        $price = (float) ($row->price ?? 0);
        $special = $row->special_price !== null ? (float) $row->special_price : null;

        if ($special === null || $special <= 0) {
            return $price;
        }

        $from = $row->special_price_from ?? null;
        $to = $row->special_price_to ?? null;
        $now = now()->startOfDay();

        if ($from && $now->lt($from)) {
            return $price;
        }

        if ($to && $now->gt($to)) {
            return $price;
        }

        return min($special, $price);
    }

    public function shouldShowStrikePrice(object $row): bool
    {
        $price = (float) ($row->price ?? 0);
        $special = $row->special_price !== null ? (float) $row->special_price : null;

        if ($special === null || $special <= 0 || $special >= $price) {
            return false;
        }

        $from = $row->special_price_from ?? null;
        $to = $row->special_price_to ?? null;
        $now = now()->startOfDay();

        if ($from && $now->lt($from)) {
            return false;
        }

        if ($to && $now->gt($to)) {
            return false;
        }

        return true;
    }

    protected function getProductFlatRow(int $productId): ?object
    {
        $channel = core()->getCurrentChannelCode();
        $locale = app()->getLocale();

        return DB::table('product_flat')
            ->where('product_id', $productId)
            ->where('channel', $channel)
            ->where('locale', $locale)
            ->whereNull('parent_id')
            ->first();
    }

    protected function formatCompactNumber(int $n): string
    {
        if ($n >= 1000) {
            return rtrim(rtrim(number_format($n / 1000, 1), '0'), '.').'k';
        }

        return (string) $n;
    }
}
