<?php

namespace Webkul\Admin\Support;

use Illuminate\Support\Facades\Route;

class SellerPanel
{
    /**
     * Whether the current route should hide Bagisto's default submenu tabs (Product Management / Categories …).
     */
    public static function hideDefaultSubmenuTabs(): bool
    {
        $route = Route::currentRouteName();

        if (! $route) {
            return false;
        }

        foreach (config('seller-panel.routes_hide_default_submenu_tabs', []) as $pattern) {
            if (self::routeMatches($route, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Resolve active tab key from current route name.
     */
    public static function activeTabKey(): string
    {
        $route = Route::currentRouteName() ?? '';

        if ($route === 'admin.dashboard.index') {
            return 'dashboard';
        }

        if ($route === 'admin.seller.purchase-history.index') {
            return 'shop_order';
        }

        foreach (config('seller-panel.tabs', []) as $tab) {
            $tabRoute = $tab['route'] ?? '';

            if ($tabRoute && Route::has($tabRoute) && $route === $tabRoute) {
                return $tab['key'] ?? '';
            }
        }

        if (str_starts_with($route, 'admin.seller.product-warehouse')) {
            return 'product_warehouse';
        }

        if (str_starts_with($route, 'admin.seller.store-products')) {
            return 'store_products';
        }

        if (str_starts_with($route, 'admin.catalog.products')) {
            return 'catalog_products';
        }

        // Prefix matches (e.g. inventory edit still warehouse tab)
        if (str_starts_with($route, 'admin.settings.inventory_sources')) {
            return 'product_warehouse';
        }

        if (str_starts_with($route, 'admin.customers.customers.review')) {
            return 'product_review';
        }

        if (str_starts_with($route, 'admin.sales.refunds')) {
            return 'refund_request';
        }

        return '';
    }

    public static function tabs(): array
    {
        return config('seller-panel.tabs', []);
    }

    protected static function routeMatches(string $route, string $pattern): bool
    {
        if (str_ends_with($pattern, '.*')) {
            $prefix = substr($pattern, 0, -2);

            return str_starts_with($route, $prefix);
        }

        return $route === $pattern;
    }
}
