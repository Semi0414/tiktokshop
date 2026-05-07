<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\ConfigurationController;

/**
 * Configuration routes.
 */
Route::get('configuration/search', [ConfigurationController::class, 'search'])->name('superadmin.configuration.search');

Route::controller(ConfigurationController::class)->prefix('configuration/{slug?}/{slug2?}')->group(function () {

    Route::get('', 'index')->name('superadmin.configuration.index');

    Route::post('', 'store')->name('superadmin.configuration.store');

    Route::get('{path}', 'download')->name('superadmin.configuration.download');
});
