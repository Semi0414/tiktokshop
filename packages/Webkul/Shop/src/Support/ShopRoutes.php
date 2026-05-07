<?php

namespace Webkul\Shop\Support;

/**
 * Route names that depend on seller-preview / home restriction settings.
 */
class ShopRoutes
{
    /**
     * Default route after login, logout, captcha default redirect, etc.
     */
    public static function publicEntryRouteName(): string
    {
        return config('seller_preview.restrict_home_to_seller_preview')
            ? 'shop.landing.index'
            : 'shop.tiktok-store.index';
    }

    /**
     * "Browse" entry (e.g. landing CTA) when /home is seller-preview-only.
     */
    public static function browseRouteName(): string
    {
        return config('seller_preview.restrict_home_to_seller_preview')
            ? 'shop.search.index'
            : 'shop.tiktok-store.index';
    }
}
