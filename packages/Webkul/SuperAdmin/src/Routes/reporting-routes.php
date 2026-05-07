<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\Reporting\CustomerController;
use Webkul\SuperAdmin\Http\Controllers\Reporting\ProductController;
use Webkul\SuperAdmin\Http\Controllers\Reporting\SaleController;

/**
 * Reporting routes.
 */
Route::prefix('reporting')->group(function () {
    /**
     * Customer routes.
     */
    Route::controller(CustomerController::class)->prefix('customers')->group(function () {
        Route::get('', 'index')->name('superadmin.reporting.customers.index');

        Route::get('stats', 'stats')->name('superadmin.reporting.customers.stats');

        Route::get('export', 'export')->name('superadmin.reporting.customers.export');

        Route::get('view', 'view')->name('superadmin.reporting.customers.view');

        Route::get('view/stats', 'viewStats')->name('superadmin.reporting.customers.view.stats');
    });

    /**
     * Product routes.
     */
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('', 'index')->name('superadmin.reporting.products.index');

        Route::get('stats', 'stats')->name('superadmin.reporting.products.stats');

        Route::get('export', 'export')->name('superadmin.reporting.products.export');

        Route::get('view', 'view')->name('superadmin.reporting.products.view');

        Route::get('view/stats', 'viewStats')->name('superadmin.reporting.products.view.stats');
    });

    /**
     * Sale routes.
     */
    Route::controller(SaleController::class)->prefix('sales')->group(function () {
        Route::get('', 'index')->name('superadmin.reporting.sales.index');

        Route::get('stats', 'stats')->name('superadmin.reporting.sales.stats');

        Route::get('export', 'export')->name('superadmin.reporting.sales.export');

        Route::get('view', 'view')->name('superadmin.reporting.sales.view');

        Route::get('view/stats', 'viewStats')->name('superadmin.reporting.sales.view.stats');
    });
});
