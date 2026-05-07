<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\Marketing\Communications\CampaignController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\Communications\EventController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\Communications\SubscriptionController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\Communications\TemplateController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\EmailManagementController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\Promotions\CartRuleController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\Promotions\CartRuleCouponController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\Promotions\CatalogRuleController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\SearchSEO\SearchSynonymController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\SearchSEO\SearchTermController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\SearchSEO\SitemapController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\SearchSEO\URLRewriteController;
use Webkul\SuperAdmin\Http\Controllers\Marketing\SellerToolsController;

/**
 * Marketing routes.
 */
Route::prefix('marketing')->group(function () {
    /**
     * Seller tools routes.
     */
    Route::controller(SellerToolsController::class)->prefix('seller-tools')->group(function () {
        Route::get('store-upgrade', 'storeUpgrade')->name('superadmin.marketing.seller_tools.store_upgrade');

        Route::get('seller-level', 'sellerLevel')->name('superadmin.marketing.seller_tools.seller_level');
    });

    /**
     * Promotions routes.
     */
    Route::prefix('promotions')->group(function () {
        /**
         * Cart rules routes.
         */
        Route::controller(CartRuleController::class)->prefix('cart-rules')->group(function () {
            Route::get('', 'index')->name('superadmin.marketing.promotions.cart_rules.index');

            Route::get('create', 'create')->name('superadmin.marketing.promotions.cart_rules.create');

            Route::post('create', 'store')->name('superadmin.marketing.promotions.cart_rules.store');

            Route::get('copy/{id}', 'copy')->name('superadmin.marketing.promotions.cart_rules.copy');

            Route::get('edit/{id}', 'edit')->name('superadmin.marketing.promotions.cart_rules.edit');

            Route::put('edit/{id}', 'update')->name('superadmin.marketing.promotions.cart_rules.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.marketing.promotions.cart_rules.delete');
        });

        /**
         * Cart rule coupons routes.
         */
        Route::controller(CartRuleCouponController::class)->prefix('cart-rules/coupons')->group(function () {
            Route::post('mass-delete', 'massDestroy')->name('superadmin.marketing.promotions.cart_rules.coupons.mass_delete');

            Route::get('{id}', 'index')->name('superadmin.marketing.promotions.cart_rules.coupons.index');

            Route::post('{id}', 'store')->name('superadmin.marketing.promotions.cart_rules.coupons.store');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.marketing.promotions.cart_rules.coupons.delete');
        });

        /**
         * Catalog rules routes.
         */
        Route::controller(CatalogRuleController::class)->prefix('catalog-rules')->group(function () {
            Route::get('', 'index')->name('superadmin.marketing.promotions.catalog_rules.index');

            Route::get('create', 'create')->name('superadmin.marketing.promotions.catalog_rules.create');

            Route::post('create', 'store')->name('superadmin.marketing.promotions.catalog_rules.store');

            Route::get('edit/{id}', 'edit')->name('superadmin.marketing.promotions.catalog_rules.edit');

            Route::put('edit/{id}', 'update')->name('superadmin.marketing.promotions.catalog_rules.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.marketing.promotions.catalog_rules.delete');
        });
    });

    /**
     * Communication routes.
     */
    Route::prefix('communications')->group(function () {
        /**
         * Super Admin outbound email (sellers, customers, custom) + log.
         */
        Route::controller(EmailManagementController::class)->prefix('email-management')->group(function () {
            Route::get('', 'index')->name('superadmin.email-management.index');

            Route::post('send', 'send')->name('superadmin.email-management.send');

            Route::delete('logs/{id}', 'destroy')->name('superadmin.email-management.logs.destroy');
        });

        /**
         * Emails templates routes.
         */
        Route::controller(TemplateController::class)->prefix('email-templates')->group(function () {
            Route::get('', 'index')->name('superadmin.marketing.communications.email_templates.index');

            Route::get('create', 'create')->name('superadmin.marketing.communications.email_templates.create');

            Route::post('create', 'store')->name('superadmin.marketing.communications.email_templates.store');

            Route::get('edit/{id}', 'edit')->name('superadmin.marketing.communications.email_templates.edit');

            Route::put('edit/{id}', 'update')->name('superadmin.marketing.communications.email_templates.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.marketing.communications.email_templates.delete');
        });

        /**
         * Events routes.
         */
        Route::controller(EventController::class)->prefix('events')->group(function () {
            Route::get('', 'index')->name('superadmin.marketing.communications.events.index');

            Route::post('create', 'store')->name('superadmin.marketing.communications.events.store');

            Route::get('edit/{id}', 'edit')->name('superadmin.marketing.communications.events.edit');

            Route::put('edit', 'update')->name('superadmin.marketing.communications.events.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.marketing.communications.events.delete');
        });

        /**
         * Campaigns routes.
         */
        Route::controller(CampaignController::class)->prefix('campaigns')->group(function () {
            Route::get('', 'index')->name('superadmin.marketing.communications.campaigns.index');

            Route::get('create', 'create')->name('superadmin.marketing.communications.campaigns.create');

            Route::post('create', 'store')->name('superadmin.marketing.communications.campaigns.store');

            Route::get('edit/{id}', 'edit')->name('superadmin.marketing.communications.campaigns.edit');

            Route::put('edit/{id}', 'update')->name('superadmin.marketing.communications.campaigns.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.marketing.communications.campaigns.delete');
        });

        /**
         * subscribers routes.
         */
        Route::controller(SubscriptionController::class)->prefix('subscribers')->group(function () {
            Route::get('', 'index')->name('superadmin.marketing.communications.subscribers.index');

            Route::get('edit/{id}', 'edit')->name('superadmin.marketing.communications.subscribers.edit');

            Route::put('edit', 'update')->name('superadmin.marketing.communications.subscribers.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.marketing.communications.subscribers.delete');
        });
    });

    /**
     * Search and SEO routes.
     */
    Route::prefix('search-seo')->group(function () {
        /**
         * URL Rewrite routes.
         */
        Route::controller(URLRewriteController::class)->prefix('url-rewrites')->group(function () {
            Route::get('', 'index')->name('superadmin.marketing.search_seo.url_rewrites.index');

            Route::post('create', 'store')->name('superadmin.marketing.search_seo.url_rewrites.store');

            Route::put('edit', 'update')->name('superadmin.marketing.search_seo.url_rewrites.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.marketing.search_seo.url_rewrites.delete');

            Route::post('mass-delete', 'massDestroy')->name('superadmin.marketing.search_seo.url_rewrites.mass_delete');
        });

        /**
         * Search Terms routes.
         */
        Route::controller(SearchTermController::class)->prefix('search-terms')->group(function () {
            Route::get('', 'index')->name('superadmin.marketing.search_seo.search_terms.index');

            Route::post('create', 'store')->name('superadmin.marketing.search_seo.search_terms.store');

            Route::put('edit', 'update')->name('superadmin.marketing.search_seo.search_terms.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.marketing.search_seo.search_terms.delete');

            Route::post('mass-delete', 'massDestroy')->name('superadmin.marketing.search_seo.search_terms.mass_delete');
        });

        /**
         * Search Synonyms routes.
         */
        Route::controller(SearchSynonymController::class)->prefix('search-synonyms')->group(function () {
            Route::get('', 'index')->name('superadmin.marketing.search_seo.search_synonyms.index');

            Route::post('create', 'store')->name('superadmin.marketing.search_seo.search_synonyms.store');

            Route::put('edit', 'update')->name('superadmin.marketing.search_seo.search_synonyms.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.marketing.search_seo.search_synonyms.delete');

            Route::post('mass-delete', 'massDestroy')->name('superadmin.marketing.search_seo.search_synonyms.mass_delete');
        });

        /**
         * Sitemaps routes.
         */
        Route::controller(SitemapController::class)->prefix('sitemaps')->group(function () {
            Route::get('', 'index')->name('superadmin.marketing.search_seo.sitemaps.index');

            Route::post('create', 'store')->name('superadmin.marketing.search_seo.sitemaps.store');

            Route::put('edit', 'update')->name('superadmin.marketing.search_seo.sitemaps.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.marketing.search_seo.sitemaps.delete');
        });
    });
});
