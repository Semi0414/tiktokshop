<?php

namespace Webkul\Shop\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Webkul\Product\Helpers\ShopCheckoutInventory;
use Webkul\Product\Models\Product;

/**
 * Aligns cart add with the PDP: only require a variant when multiple active options exist.
 */
class ConfigurableCartSelection
{
    /**
     * Variants shown on configurable-html (status = enabled).
     */
    public static function activeVariants(Product $product): Collection
    {
        if ($product->type !== 'configurable') {
            return collect();
        }

        $product->loadMissing('variants');

        return $product->variants
            ->filter(fn (Product $variant) => (bool) $variant->status)
            ->values();
    }

    /**
     * Auto-fill selected_configurable_option when the PDP has no dropdown (0–1 active variants).
     */
    public static function applyToRequest(Product $product, Request $request): void
    {
        if ($product->type !== 'configurable' || $request->filled('selected_configurable_option')) {
            return;
        }

        $product->loadMissing('variants');

        $active = self::activeVariants($product);

        if ($active->count() === 1) {
            $request->merge(['selected_configurable_option' => $active->first()->id]);

            return;
        }

        if ($active->count() > 1) {
            return;
        }

        // No active variants on PDP — single child or checkout inventory bypass.
        if ($product->variants->count() === 1) {
            $request->merge(['selected_configurable_option' => $product->variants->first()->id]);

            return;
        }

        if ($product->variants->isNotEmpty()) {
            $fallback = $product->variants->first(fn (Product $variant) => (bool) $variant->status)
                ?? $product->variants->first();

            if ($fallback) {
                $request->merge(['selected_configurable_option' => $fallback->id]);
            }
        }
    }

    /**
     * User must pick from dropdown (multiple active variants, none selected).
     */
    public static function requiresUserSelection(Product $product, Request $request): bool
    {
        if ($product->type !== 'configurable' || $request->filled('selected_configurable_option')) {
            return false;
        }

        return self::activeVariants($product)->count() > 1;
    }
}
