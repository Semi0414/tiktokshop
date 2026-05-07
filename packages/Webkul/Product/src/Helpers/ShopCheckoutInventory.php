<?php

namespace Webkul\Product\Helpers;

class ShopCheckoutInventory
{
    /**
     * When true, storefront skips stock/inventory and catalog quantity limits for checkout.
     */
    public static function shouldSkipInventoryChecks(): bool
    {
        return (bool) config('shop-checkout.skip_inventory_and_purchase_limits', false);
    }
}
