<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\HomePageMediaController;

/**
 * Laravel 11+: explicit action routes (Route::controller() was removed).
 */
Route::prefix('home-page-media')->group(function () {
    Route::get('', [HomePageMediaController::class, 'index'])->name('superadmin.home_page_media.index');

    Route::post('', [HomePageMediaController::class, 'store'])->name('superadmin.home_page_media.store');

    Route::get('{id}/edit', [HomePageMediaController::class, 'edit'])->name('superadmin.home_page_media.edit');

    Route::put('{id}', [HomePageMediaController::class, 'update'])->name('superadmin.home_page_media.update');

    Route::delete('{id}', [HomePageMediaController::class, 'destroy'])->name('superadmin.home_page_media.destroy');
});
