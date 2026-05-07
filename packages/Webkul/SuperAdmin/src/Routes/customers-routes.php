<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\Customers\AddressController;
use Webkul\SuperAdmin\Http\Controllers\Customers\Customer\CartController;
use Webkul\SuperAdmin\Http\Controllers\Customers\Customer\CompareController;
use Webkul\SuperAdmin\Http\Controllers\Customers\Customer\OrderController;
use Webkul\SuperAdmin\Http\Controllers\Customers\Customer\WishlistController;
use Webkul\SuperAdmin\Http\Controllers\Customers\CustomerController;
use Webkul\SuperAdmin\Http\Controllers\Customers\CustomerGroupController;
use Webkul\SuperAdmin\Http\Controllers\Customers\GDPRController;
use Webkul\SuperAdmin\Http\Controllers\Customers\ReviewController;

/**
 * Customers routes.
 */
Route::prefix('customers')->group(function () {
    /**
     * Customer management routes.
     */
    Route::controller(CustomerController::class)->group(function () {
        Route::get('', 'index')->name('superadmin.customers.customers.index');

        Route::get('view/{id}', 'show')->name('superadmin.customers.customers.view');

        Route::post('create', 'store')->name('superadmin.customers.customers.store');

        Route::get('search', 'search')->name('superadmin.customers.customers.search');

        Route::get('login-as-customer/{id}', 'loginAsCustomer')->name('superadmin.customers.customers.login_as_customer');

        Route::post('note/{id}', 'storeNotes')->name('superadmin.customer.note.store');

        Route::put('edit/{id}', 'update')->name('superadmin.customers.customers.update');

        Route::post('wallet/{id}', 'updateWallet')->name('superadmin.customers.customers.wallet.update');

        Route::post('mass-delete', 'massDestroy')->name('superadmin.customers.customers.mass_delete');

        Route::post('mass-update', 'massUpdate')->name('superadmin.customers.customers.mass_update');

        Route::post('{id}', 'destroy')->name('superadmin.customers.customers.delete');

        Route::controller(WishlistController::class)->group(function () {
            Route::get('{id}/wishlist-items', 'items')->name('superadmin.customers.customers.wishlist.items');

            Route::delete('{id}/wishlist-items', 'destroy')->name('superadmin.customers.customers.wishlist.items.delete');
        });

        Route::controller(CompareController::class)->group(function () {
            Route::get('{id}/compare-items', 'items')->name('superadmin.customers.customers.compare.items');

            Route::delete('{id}/compare-items', 'destroy')->name('superadmin.customers.customers.compare.items.delete');
        });

        Route::controller(CartController::class)->prefix('{id}/cart')->group(function () {
            Route::post('create', 'store')->name('superadmin.customers.customers.cart.store');

            Route::get('items', 'items')->name('superadmin.customers.customers.cart.items');

            Route::delete('items', 'destroy')->name('superadmin.customers.customers.cart.items.delete');
        });

        Route::controller(OrderController::class)->group(function () {
            Route::get('{id}/recent-order-items', 'recentItems')->name('superadmin.customers.customers.orders.recent_items');
        });
    });

    /**
     * Customer's addresses routes.
     */
    Route::controller(AddressController::class)->group(function () {
        Route::prefix('{id}/addresses')->group(function () {
            Route::get('', 'index')->name('superadmin.customers.customers.addresses.index');

            Route::get('create', 'create')->name('superadmin.customers.customers.addresses.create');

            Route::post('create', 'store')->name('superadmin.customers.customers.addresses.store');
        });

        Route::prefix('addresses')->group(function () {
            Route::get('edit/{id}', 'edit')->name('superadmin.customers.customers.addresses.edit');

            Route::put('edit/{id}', 'update')->name('superadmin.customers.customers.addresses.update');

            Route::post('default/{id}', 'makeDefault')->name('superadmin.customers.customers.addresses.set_default');

            Route::post('delete/{id}', 'destroy')->name('superadmin.customers.customers.addresses.delete');
        });
    });

    /**
     * Customer's reviews routes.
     */
    Route::controller(ReviewController::class)->prefix('reviews')->group(function () {
        Route::get('', 'index')->name('superadmin.customers.customers.review.index');

        Route::get('edit/{id}', 'edit')->name('superadmin.customers.customers.review.edit');

        Route::put('edit/{id}', 'update')->name('superadmin.customers.customers.review.update');

        Route::delete('/{id}', 'destroy')->name('superadmin.customers.customers.review.delete');

        Route::post('mass-delete', 'massDestroy')->name('superadmin.customers.customers.review.mass_delete');

        Route::post('mass-update', 'massUpdate')->name('superadmin.customers.customers.review.mass_update');
    });

    /**
     * Customer groups routes.
     */
    Route::controller(CustomerGroupController::class)->prefix('groups')->group(function () {
        Route::get('', 'index')->name('superadmin.customers.groups.index');

        Route::post('create', 'store')->name('superadmin.customers.groups.store');

        Route::put('edit', 'update')->name('superadmin.customers.groups.update');

        Route::delete('delete/{id}', 'destroy')->name('superadmin.customers.groups.delete');
    });

    Route::controller(GDPRController::class)->prefix('gdpr')->group(function () {
        Route::get('', 'index')->name('superadmin.customers.gdpr.index');

        Route::get('edit/{id}', 'edit')->name('superadmin.customers.gdpr.edit');

        Route::put('edit/{id}', 'update')->name('superadmin.customers.gdpr.update');

        Route::delete('delete/{id}', 'delete')->name('superadmin.customers.gdpr.delete');
    });
});
