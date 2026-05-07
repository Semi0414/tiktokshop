<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\BookingProductController;
use Webkul\Shop\Http\Controllers\CompareController;
use Webkul\Shop\Http\Controllers\HomeController;
use Webkul\Shop\Http\Controllers\HomePageMediaFileController;
use Webkul\Shop\Http\Controllers\LandingController;
use Webkul\Shop\Http\Controllers\PageController;
use Webkul\Shop\Http\Controllers\ProductController;
use Webkul\Shop\Http\Controllers\ProductsCategoriesProxyController;
use Webkul\Shop\Http\Controllers\SearchController;
use Webkul\Shop\Http\Controllers\SubscriptionController;
use Webkul\Shop\Http\Controllers\TikStoreController;
use Webkul\Shop\Http\Controllers\VisitSellerController;

/**
 * CMS pages.
 */
Route::get('page/{slug}', [PageController::class, 'view'])
    ->name('shop.cms.page')
    ->middleware('cache.response');

/**
 * Home page media: serve from public disk (works when /storage symlink or permissions break).
 */
Route::get('hpm-file/{file}', [HomePageMediaFileController::class, 'show'])
    ->name('shop.home_page_media.file')
    ->where('file', '[A-Za-z0-9._\-]+');

/**
 * Store front landing (Buyer / Seller / Join).
 */
Route::controller(LandingController::class)->group(function () {
    Route::get('/', 'index')->name('shop.landing.index');
    Route::get('/join', 'joinForm')->name('shop.landing.join');
    Route::post('/join', 'submitJoin')->name('shop.landing.join.submit');
});

/**
 * Existing shop home moved to /home.
 */
Route::get('/home', [HomeController::class, 'index'])
    ->name('shop.home.index')
    ->middleware('cache.response');

Route::get('contact-us', [HomeController::class, 'contactUs'])
    ->name('shop.home.contact_us')
    ->middleware('cache.response');

Route::post('contact-us/send-mail', [HomeController::class, 'sendContactUsMail'])
    ->name('shop.home.contact_us.send_mail')
    ->middleware('cache.response');

/**
 * Store front search.
 */
Route::get('search', [SearchController::class, 'index'])
    ->name('shop.search.index')
    ->middleware('cache.response');

Route::post('search/upload', [SearchController::class, 'upload'])->name('shop.search.upload');

/**
 * Subscription routes.
 */
Route::controller(SubscriptionController::class)->group(function () {
    Route::post('subscription', 'store')->name('shop.subscription.store');

    Route::get('subscription/{token}', 'destroy')->name('shop.subscription.destroy');
});

/**
 * Compare products
 */
Route::get('compare', [CompareController::class, 'index'])
    ->name('shop.compare.index')
    ->middleware('cache.response');

/**
 * Downloadable products
 */
Route::controller(ProductController::class)->group(function () {
    Route::get('downloadable/download-sample/{type}/{id}', 'downloadSample')->name('shop.downloadable.download_sample');

    Route::get('product/{id}/{attribute_id}', 'download')->name('shop.product.file.download');
});

/**
 * Booking products
 */
Route::get('booking-slots/{id}', [BookingProductController::class, 'index'])
    ->name('shop.booking-product.slots.index');

/**
 * TikStore-style browse (categories, recommended, seller search).
 */
Route::controller(TikStoreController::class)->group(function () {
    Route::get('tik-store', 'index')->name('shop.tik-store.index');
    /** Alias for TikStore browse (same as tik-store). */
    Route::get('tiktok-store', 'index')->name('shop.tiktok-store.index');
    Route::get('tik-store/recommended', 'recommended')->name('shop.tik-store.recommended');
    Route::get('tik-store/products', 'allProducts')->name('shop.tik-store.products');
});

/**
 * Visit a seller's storefront (sets seller preview session, same as admin "Visit store").
 */
Route::get('visit-seller/{seller}', VisitSellerController::class)
    ->name('shop.seller.visit');

/**
 * Fallback route (must be registered after all explicit storefront paths, e.g. /home).
 */
Route::fallback(ProductsCategoriesProxyController::class.'@index')
    ->name('shop.product_or_category.index')
    ->middleware('cache.response');
