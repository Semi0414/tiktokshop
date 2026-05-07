<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\Sellers\SellerApplicationController;
use Webkul\SuperAdmin\Http\Controllers\Sellers\SellerController;
use Webkul\SuperAdmin\Http\Controllers\Sellers\SellerOrderController;

Route::get('sellers', [SellerController::class, 'index'])->name('superadmin.sellers.index');

Route::post('sellers/create', [SellerController::class, 'store'])->name('superadmin.sellers.store');

Route::post('sellers/mass-delete', [SellerController::class, 'massDestroy'])->name('superadmin.sellers.mass_delete');

Route::post('sellers/mass-update', [SellerController::class, 'massUpdate'])->name('superadmin.sellers.mass_update');

Route::get('sellers/login-as-seller/{id}', [SellerController::class, 'loginAsSeller'])->name('superadmin.sellers.login_as_seller');

Route::get('sellers/{id}/visit-store', [SellerController::class, 'visitStoreFront'])->name('superadmin.sellers.visit_store');

Route::post('sellers/{id}/welcome-email', [SellerController::class, 'sendWelcomeEmail'])->name('superadmin.sellers.welcome_email');

Route::get('sellers/orders', [SellerOrderController::class, 'index'])->name('superadmin.sellers.orders.index');
Route::get('sellers/orders/dashboard', [SellerOrderController::class, 'dashboard'])->name('superadmin.sellers.orders.dashboard');
Route::post('sellers/orders/bulk-status', [SellerOrderController::class, 'bulkUpdateStatus'])->name('superadmin.sellers.orders.bulk-status');
Route::post('sellers/orders/{id}/approve', [SellerOrderController::class, 'approve'])->name('superadmin.sellers.orders.approve');
Route::post('sellers/orders/{id}/reject', [SellerOrderController::class, 'reject'])->name('superadmin.sellers.orders.reject');

Route::get('sellers/view/{id}', [SellerController::class, 'show'])->name('superadmin.sellers.view');
Route::get('sellers/view/{id}/orders-data', [SellerController::class, 'ordersData'])->name('superadmin.sellers.view.orders_data');

Route::post('sellers/note/{id}', [SellerController::class, 'storeNote'])->name('superadmin.sellers.note.store');

Route::post('sellers/account/{id}', [SellerController::class, 'destroy'])->name('superadmin.sellers.destroy');

Route::put('sellers/profile/{id}', [SellerController::class, 'updateProfile'])->name('superadmin.sellers.profile.update');

Route::get('sellers/edit/{id}', [SellerController::class, 'edit'])->name('superadmin.sellers.edit');

Route::put('sellers/edit/{id}', [SellerController::class, 'update'])->name('superadmin.sellers.update');

Route::get('sellers/{id}/edit', function ($id) {
    return redirect()->route('superadmin.sellers.edit', $id);
});

Route::get('seller-applications', [SellerApplicationController::class, 'index'])->name('superadmin.sellers.applications.index');
Route::get('seller-applications/{id}', [SellerApplicationController::class, 'view'])->name('superadmin.sellers.applications.view');
Route::post('seller-applications/{id}/status', [SellerApplicationController::class, 'updateStatus'])->name('superadmin.sellers.applications.status');
