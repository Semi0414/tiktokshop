<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Http\Controllers\User\ForgetPasswordController;
use Webkul\SuperAdmin\Http\Controllers\User\ResetPasswordController;
use Webkul\SuperAdmin\Http\Controllers\User\SessionController;

/**
 * Auth routes.
 */
Route::group(['prefix' => config('app.super_admin_url')], function () {
    /**
     * Redirect route.
     */
    Route::get('/', [Controller::class, 'redirectToLogin']);

    Route::controller(SessionController::class)->prefix('login')->group(function () {
        /**
         * Login routes.
         */
        Route::get('', 'create')->name('superadmin.session.create');

        /**
         * Login post route to admin auth controller.
         */
        Route::post('', 'store')->name('superadmin.session.store');
    });

    /**
     * Forget password routes.
     */
    Route::controller(ForgetPasswordController::class)->prefix('forget-password')->group(function () {
        Route::get('', 'create')->name('superadmin.forget_password.create');

        Route::post('', 'store')->name('superadmin.forget_password.store');
    });

    /**
     * Reset password routes.
     */
    Route::controller(ResetPasswordController::class)->prefix('reset-password')->group(function () {
        Route::get('{token}', 'create')->name('superadmin.reset_password.create');

        Route::post('', 'store')->name('superadmin.reset_password.store');
    });
});
