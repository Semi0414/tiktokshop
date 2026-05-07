<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\CMS\PageController;

/**
 * CMS routes.
 */
Route::controller(PageController::class)->prefix('cms')->group(function () {
    Route::get('/', 'index')->name('superadmin.cms.index');

    Route::get('create', 'create')->name('superadmin.cms.create');

    Route::post('create', 'store')->name('superadmin.cms.store');

    Route::get('edit/{id}', 'edit')->name('superadmin.cms.edit');

    Route::put('edit/{id}', 'update')->name('superadmin.cms.update');

    Route::delete('edit/{id}', 'delete')->name('superadmin.cms.delete');

    Route::post('mass-delete', 'massDelete')->name('superadmin.cms.mass_delete');
});
