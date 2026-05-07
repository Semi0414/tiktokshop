<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\Settings\ChannelController;
use Webkul\SuperAdmin\Http\Controllers\Settings\CryptoPayoutAddressController;
use Webkul\SuperAdmin\Http\Controllers\Settings\CurrencyController;
use Webkul\SuperAdmin\Http\Controllers\Settings\DataTransfer\ImportController;
use Webkul\SuperAdmin\Http\Controllers\Settings\ExchangeRateController;
use Webkul\SuperAdmin\Http\Controllers\Settings\InventorySourceController;
use Webkul\SuperAdmin\Http\Controllers\Settings\LocaleController;
use Webkul\SuperAdmin\Http\Controllers\Settings\RoleController;
use Webkul\SuperAdmin\Http\Controllers\Settings\Tax\TaxCategoryController;
use Webkul\SuperAdmin\Http\Controllers\Settings\Tax\TaxRateController;
use Webkul\SuperAdmin\Http\Controllers\Settings\ThemeController;
use Webkul\SuperAdmin\Http\Controllers\Settings\UserController;

/**
 * Settings routes.
 */
Route::prefix('settings')->group(function () {
    /**
     * Channels routes.
     */
    Route::controller(ChannelController::class)->prefix('channels')->group(function () {
        Route::get('', 'index')->name('superadmin.settings.channels.index');

        Route::get('create', 'create')->name('superadmin.settings.channels.create');

        Route::post('create', 'store')->name('superadmin.settings.channels.store');

        Route::get('edit/{id}', 'edit')->name('superadmin.settings.channels.edit');

        Route::put('edit/{id}', 'update')->name('superadmin.settings.channels.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.settings.channels.delete');
    });

    /**
     * Currencies routes.
     */
    Route::controller(CurrencyController::class)->prefix('currencies')->group(function () {
        Route::get('', 'index')->name('superadmin.settings.currencies.index');

        Route::post('create', 'store')->name('superadmin.settings.currencies.store');

        Route::get('edit/{id}', 'edit')->name('superadmin.settings.currencies.edit');

        Route::put('edit', 'update')->name('superadmin.settings.currencies.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.settings.currencies.delete');

        Route::post('mass-delete', 'massDestroy')->name('superadmin.settings.currencies.mass_delete');
    });

    /**
     * Exchange rates routes.
     */
    Route::controller(ExchangeRateController::class)->prefix('exchange-rates')->group(function () {
        Route::get('', 'index')->name('superadmin.settings.exchange_rates.index');

        Route::post('create', 'store')->name('superadmin.settings.exchange_rates.store');

        Route::get('edit/{id}', 'edit')->name('superadmin.settings.exchange_rates.edit');

        Route::get('update-rates', 'updateRates')->name('superadmin.settings.exchange_rates.update_rates');

        Route::put('edit', 'update')->name('superadmin.settings.exchange_rates.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.settings.exchange_rates.delete');
    });

    /**
     * Locales routes.
     */
    Route::controller(LocaleController::class)->prefix('locales')->group(function () {
        Route::get('', 'index')->name('superadmin.settings.locales.index');

        Route::post('create', 'store')->name('superadmin.settings.locales.store');

        Route::get('edit/{id}', 'edit')->name('superadmin.settings.locales.edit');

        Route::put('edit', 'update')->name('superadmin.settings.locales.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.settings.locales.delete');
    });

    /**
     * Inventory sources routes.
     */
    Route::controller(InventorySourceController::class)->prefix('inventory-sources')->group(function () {
        Route::get('', 'index')->name('superadmin.settings.inventory_sources.index');

        Route::get('create', 'create')->name('superadmin.settings.inventory_sources.create');

        Route::post('create', 'store')->name('superadmin.settings.inventory_sources.store');

        Route::get('edit/{id}', 'edit')->name('superadmin.settings.inventory_sources.edit');

        Route::put('edit/{id}', 'update')->name('superadmin.settings.inventory_sources.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.settings.inventory_sources.delete');
    });

    Route::prefix('taxes')->group(function () {
        /**
         * Tax categories routes.
         */
        Route::controller(TaxCategoryController::class)->prefix('categories')->group(function () {
            Route::get('', 'index')->name('superadmin.settings.taxes.categories.index');

            Route::post('', 'store')->name('superadmin.settings.taxes.categories.store');

            Route::get('edit/{id}', 'edit')->name('superadmin.settings.taxes.categories.edit');

            Route::put('edit', 'update')->name('superadmin.settings.taxes.categories.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.settings.taxes.categories.delete');
        });

        /**
         * Tax rates routes.
         */
        Route::controller(TaxRateController::class)->prefix('rates')->group(function () {
            Route::get('', 'index')->name('superadmin.settings.taxes.rates.index');

            Route::get('create', 'create')->name('superadmin.settings.taxes.rates.create');

            Route::post('create', 'store')->name('superadmin.settings.taxes.rates.store');

            Route::get('edit/{id}', 'edit')->name('superadmin.settings.taxes.rates.edit');

            Route::put('edit/{id}', 'update')->name('superadmin.settings.taxes.rates.update');

            Route::delete('edit/{id}', 'destroy')->name('superadmin.settings.taxes.rates.delete');
        });
    });

    /**
     * Roles routes.
     */
    Route::controller(RoleController::class)->prefix('roles')->group(function () {
        Route::get('', 'index')->name('superadmin.settings.roles.index');

        Route::get('create', 'create')->name('superadmin.settings.roles.create');

        Route::post('create', 'store')->name('superadmin.settings.roles.store');

        Route::get('edit/{id}', 'edit')->name('superadmin.settings.roles.edit');

        Route::put('edit/{id}', 'update')->name('superadmin.settings.roles.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.settings.roles.delete');
    });

    /**
     * Users routes.
     */
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('', 'index')->name('superadmin.settings.users.index');

        Route::post('create', 'store')->name('superadmin.settings.users.store');

        Route::get('edit/{id}', 'edit')->name('superadmin.settings.users.edit');

        Route::put('edit', 'update')->name('superadmin.settings.users.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.settings.users.delete');

        Route::put('confirm', 'destroySelf')->name('superadmin.settings.users.destroy');
    });

    Route::controller(ThemeController::class)->prefix('themes')->group(function () {
        Route::get('', 'index')->name('superadmin.settings.themes.index');

        Route::get('edit/{id}', 'edit')->name('superadmin.settings.themes.edit');

        Route::post('store', 'store')->name('superadmin.settings.themes.store');

        Route::post('edit/{id}', 'update')->name('superadmin.settings.themes.update');

        Route::delete('edit/{id}', 'destroy')->name('superadmin.settings.themes.delete');

        Route::post('mass-update', 'massUpdate')->name('superadmin.settings.themes.mass_update');

        Route::post('mass-delete', 'massDestroy')->name('superadmin.settings.themes.mass_delete');
    });

    /**
     * Data Transfer routes.
     */
    Route::prefix('data-transfer')->group(function () {
        /**
         * Import routes.
         */
        Route::controller(ImportController::class)->prefix('imports')->group(function () {
            Route::get('', 'index')->name('superadmin.settings.data_transfer.imports.index');

            Route::get('create', 'create')->name('superadmin.settings.data_transfer.imports.create');

            Route::post('create', 'store')->name('superadmin.settings.data_transfer.imports.store');

            Route::get('edit/{id}', 'edit')->name('superadmin.settings.data_transfer.imports.edit');

            Route::put('update/{id}', 'update')->name('superadmin.settings.data_transfer.imports.update');

            Route::delete('destroy/{id}', 'destroy')->name('superadmin.settings.data_transfer.imports.delete');

            Route::get('import/{id}', 'import')->name('superadmin.settings.data_transfer.imports.import');

            Route::get('validate/{id}', 'validateImport')->name('superadmin.settings.data_transfer.imports.validate');

            Route::get('start/{id}', 'start')->name('superadmin.settings.data_transfer.imports.start');

            Route::get('link/{id}', 'link')->name('superadmin.settings.data_transfer.imports.link');

            Route::get('index/{id}', 'indexData')->name('superadmin.settings.data_transfer.imports.index_data');

            Route::get('stats/{id}/{state?}', 'stats')->name('superadmin.settings.data_transfer.imports.stats');

            Route::get('download-sample/{type}/{format}', 'downloadSample')->name('superadmin.settings.data_transfer.imports.download_sample');

            Route::get('download/{id}', 'download')->name('superadmin.settings.data_transfer.imports.download');

            Route::get('download-error-report/{id}', 'downloadErrorReport')->name('superadmin.settings.data_transfer.imports.download_error_report');
        });
    });

    /**
     * Crypto payout addresses (saved for reference / payouts).
     */
    Route::controller(CryptoPayoutAddressController::class)->prefix('crypto-addresses')->group(function () {
        Route::get('', 'index')->name('superadmin.settings.crypto_addresses.index');

        Route::post('', 'store')->name('superadmin.settings.crypto_addresses.store');

        Route::get('{id}/edit', 'edit')->name('superadmin.settings.crypto_addresses.edit');

        Route::put('{id}', 'update')->name('superadmin.settings.crypto_addresses.update');

        Route::delete('{id}', 'destroy')->name('superadmin.settings.crypto_addresses.destroy');
    });
});
