<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\DashboardController;
use Webkul\SuperAdmin\Http\Controllers\DataGrid\DataGridController;
use Webkul\SuperAdmin\Http\Controllers\DataGrid\SavedFilterController;
use Webkul\SuperAdmin\Http\Controllers\MagicAIController;
use Webkul\SuperAdmin\Http\Controllers\TinyMCEController;
use Webkul\SuperAdmin\Http\Controllers\User\AccountController;
use Webkul\SuperAdmin\Http\Controllers\User\SessionController;

/**
 * Dashboard routes.
 */
Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
    Route::get('', 'index')->name('superadmin.dashboard.index');
});

/**
 * Datagrid routes.
 */
Route::controller(DataGridController::class)->prefix('datagrid')->group(function () {
    Route::get('look-up', 'lookUp')->name('superadmin.datagrid.look_up');

    Route::controller(SavedFilterController::class)->prefix('saved-filters')->group(function () {
        Route::post('', 'store')->name('superadmin.datagrid.saved_filters.store');

        Route::get('', 'get')->name('superadmin.datagrid.saved_filters.index');

        Route::put('{id}', 'update')->name('superadmin.datagrid.saved_filters.update');

        Route::delete('{id}', 'destroy')->name('superadmin.datagrid.saved_filters.destroy');
    });
});

/**
 * Tinymce file upload handler.
 */
Route::post('tinymce/upload', [TinyMCEController::class, 'upload'])->name('superadmin.tinymce.upload');

/**
 * AI Routes
 */
Route::controller(MagicAIController::class)->prefix('magic-ai')->group(function () {
    Route::post('content', 'content')->name('superadmin.magic_ai.content');

    Route::post('image', 'image')->name('superadmin.magic_ai.image');
});

/**
 * Admin profile routes.
 */
Route::controller(AccountController::class)->prefix('account')->group(function () {
    Route::get('', 'edit')->name('superadmin.account.edit');

    Route::put('', 'update')->name('superadmin.account.update');
});

Route::delete('logout', [SessionController::class, 'destroy'])->name('superadmin.session.destroy');
