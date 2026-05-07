<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\Catalog\AttributeController;
use Webkul\SuperAdmin\Http\Controllers\Catalog\AttributeFamilyController;
use Webkul\SuperAdmin\Http\Controllers\Catalog\CategoryController;
use Webkul\SuperAdmin\Http\Controllers\Catalog\Product\BundleController;
use Webkul\SuperAdmin\Http\Controllers\Catalog\Product\ConfigurableController;
use Webkul\SuperAdmin\Http\Controllers\Catalog\Product\DownloadableController;
use Webkul\SuperAdmin\Http\Controllers\Catalog\Product\GroupedController;
use Webkul\SuperAdmin\Http\Controllers\Catalog\Product\SimpleController;
use Webkul\SuperAdmin\Http\Controllers\Catalog\Product\VirtualController;
use Webkul\SuperAdmin\Http\Controllers\Catalog\ProductController;

/**
 * Catalog routes.
 */
Route::prefix('catalog')->group(function () {
    /**
     * Attributes routes.
     */
    Route::controller(AttributeController::class)->prefix('attributes')->group(function () {
        Route::get('', 'index')->name('superadmin.catalog.attributes.index');

        Route::get('{id}/options', 'getAttributeOptions')->name('superadmin.catalog.attributes.options');

        Route::get('create', 'create')->name('superadmin.catalog.attributes.create');

        Route::post('create', 'store')->name('superadmin.catalog.attributes.store');

        Route::get('edit/{id}', 'edit')->name('superadmin.catalog.attributes.edit');

        Route::put('edit/{id}', 'update')->name('superadmin.catalog.attributes.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.catalog.attributes.delete');

        Route::post('mass-delete', 'massDestroy')->name('superadmin.catalog.attributes.mass_delete');
    });

    /**
     * Attribute families routes.
     */
    Route::controller(AttributeFamilyController::class)->prefix('families')->group(function () {
        Route::get('', 'index')->name('superadmin.catalog.families.index');

        Route::get('create', 'create')->name('superadmin.catalog.families.create');

        Route::post('create', 'store')->name('superadmin.catalog.families.store');

        Route::get('edit/{id}', 'edit')->name('superadmin.catalog.families.edit');

        Route::put('edit/{id}', 'update')->name('superadmin.catalog.families.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.catalog.families.delete');
    });

    /**
     * Categories routes.
     */
    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('', 'index')->name('superadmin.catalog.categories.index');

        Route::get('create', 'create')->name('superadmin.catalog.categories.create');

        Route::post('create', 'store')->name('superadmin.catalog.categories.store');

        Route::get('edit/{id}', 'edit')->name('superadmin.catalog.categories.edit');

        Route::put('edit/{id}', 'update')->name('superadmin.catalog.categories.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.catalog.categories.delete');

        Route::post('mass-delete', 'massDestroy')->name('superadmin.catalog.categories.mass_delete');

        Route::post('mass-update', 'massUpdate')->name('superadmin.catalog.categories.mass_update');

        Route::get('search', 'search')->name('superadmin.catalog.categories.search');

        Route::get('tree', 'tree')->name('superadmin.catalog.categories.tree');
    });

    /**
     * Sync route.
     */
    Route::get('/sync', [ProductController::class, 'sync']);

    /**
     * Products routes.
     */
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('', 'index')->name('superadmin.catalog.products.index');

        Route::post('create', 'store')->name('superadmin.catalog.products.store');

        Route::post('copy/{id}', 'copy')->name('superadmin.catalog.products.copy');

        Route::get('edit/{id}', 'edit')->name('superadmin.catalog.products.edit');

        Route::put('edit/{id}', 'update')->name('superadmin.catalog.products.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.catalog.products.delete');

        Route::put('edit/{id}/inventories', 'updateInventories')->name('superadmin.catalog.products.update_inventories');

        Route::post('upload-file/{id}', 'uploadLink')->name('superadmin.catalog.products.upload_link');

        Route::post('upload-sample/{id}', 'uploadSample')->name('superadmin.catalog.products.upload_sample');

        Route::post('mass-update', 'massUpdate')->name('superadmin.catalog.products.mass_update');

        Route::post('mass-delete', 'massDestroy')->name('superadmin.catalog.products.mass_delete');

        Route::controller(SimpleController::class)->group(function () {
            Route::get('{id}/simple-customizable-options', 'customizableOptions')->name('superadmin.catalog.products.simple.customizable-options');
        });

        Route::controller(ConfigurableController::class)->group(function () {
            Route::get('{id}/configurable-options', 'options')->name('superadmin.catalog.products.configurable.options');
        });

        Route::controller(BundleController::class)->group(function () {
            Route::get('{id}/bundle-options', 'options')->name('superadmin.catalog.products.bundle.options');
        });

        Route::controller(GroupedController::class)->group(function () {
            Route::get('{id}/grouped-options', 'options')->name('superadmin.catalog.products.grouped.options');
        });

        Route::controller(DownloadableController::class)->group(function () {
            Route::get('{id}/downloadable-options', 'options')->name('superadmin.catalog.products.downloadable.options');
        });

        Route::controller(VirtualController::class)->group(function () {
            Route::get('{id}/virtual-customizable-options', 'customizableOptions')->name('superadmin.catalog.products.virtual.customizable-options');
        });

        Route::get('search', 'search')->name('superadmin.catalog.products.search');

        Route::get('{id}/{attribute_id}', 'download')->name('superadmin.catalog.products.file.download');
    });
});
