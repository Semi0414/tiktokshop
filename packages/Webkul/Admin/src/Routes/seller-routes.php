<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Seller\FinancialStatementController;
use Webkul\Admin\Http\Controllers\Seller\FundRecordController;
use Webkul\Admin\Http\Controllers\Seller\ProductWarehouseController;
use Webkul\Admin\Http\Controllers\Seller\PurchaseHistoryController;
use Webkul\Admin\Http\Controllers\Seller\SellerLevelController;
use Webkul\Admin\Http\Controllers\Seller\SellerShopOrderController;
use Webkul\Admin\Http\Controllers\Seller\SellerStoreProductsController;
use Webkul\Admin\Http\Controllers\Seller\SellerVisitStoreController;
use Webkul\Admin\Http\Controllers\Seller\StoreUpgradeController;
use Webkul\Admin\Http\Controllers\Seller\WalletController;

/**
 * Seller panel routes (Admin package repurposed).
 */
Route::get('financial-statement', [FinancialStatementController::class, 'index'])->name('admin.seller.financial-statement.index');
Route::get('fund-record', [FundRecordController::class, 'index'])->name('admin.seller.fund-record.index');

Route::get('purchase-history', [PurchaseHistoryController::class, 'index'])->name('admin.seller.purchase-history.index');

Route::get('wallet', [WalletController::class, 'index'])->name('admin.wallet.index');

Route::post('shop-order/{order}/make-order', [SellerShopOrderController::class, 'makeOrder'])->name('admin.seller.shop-order.make-order');
Route::post('shop-order/bulk-make-order', [SellerShopOrderController::class, 'bulkMakeOrder'])->name('admin.seller.shop-order.bulk-make-order');
Route::post('wallet/deposit-request', [WalletController::class, 'depositRequest'])->name('admin.wallet.deposit-request');
Route::post('wallet/withdraw-request', [WalletController::class, 'withdrawRequest'])->name('admin.wallet.withdraw-request');

Route::get('store-upgrade', [StoreUpgradeController::class, 'index'])->name('admin.store-upgrade.index');
Route::get('seller-level', [SellerLevelController::class, 'index'])->name('admin.seller-level.index');

Route::get('visit-store', SellerVisitStoreController::class)->name('admin.seller.visit-store');

Route::get('product-warehouse', [ProductWarehouseController::class, 'index'])->name('admin.seller.product-warehouse.index');
Route::post('product-warehouse/commission', [ProductWarehouseController::class, 'saveCommission'])->name('admin.seller.product-warehouse.commission');
Route::post('product-warehouse/attach', [ProductWarehouseController::class, 'attach'])->name('admin.seller.product-warehouse.attach');
Route::get('product-warehouse/attach-one/{productId}', [ProductWarehouseController::class, 'attachOne'])->name('admin.seller.product-warehouse.attach-one');

Route::get('store-products', [SellerStoreProductsController::class, 'index'])->name('admin.seller.store-products.index');
Route::post('store-products/mass-destroy-by-product-ids', [SellerStoreProductsController::class, 'massDestroyByProductIds'])->name('admin.seller.store-products.mass-destroy-by-product-ids');
Route::post('store-products/mass-destroy', [SellerStoreProductsController::class, 'massDestroy'])->name('admin.seller.store-products.mass-destroy');
Route::post('store-products/bulk-update', [SellerStoreProductsController::class, 'bulkUpdate'])->name('admin.seller.store-products.bulk-update');
Route::get('store-products/{sellerStoreProduct}/modal-data', [SellerStoreProductsController::class, 'modalData'])->name('admin.seller.store-products.modal-data');
Route::put('store-products/{sellerStoreProduct}', [SellerStoreProductsController::class, 'updateStoreProduct'])->name('admin.seller.store-products.update');
Route::get('store-products/{sellerStoreProduct}/edit-commission', [SellerStoreProductsController::class, 'editCommission'])->name('admin.seller.store-products.edit-commission');
Route::put('store-products/{sellerStoreProduct}/commission', [SellerStoreProductsController::class, 'updateCommission'])->name('admin.seller.store-products.update-commission');
Route::post('store-products/{sellerStoreProduct}/toggle-recommended', [SellerStoreProductsController::class, 'toggleRecommended'])->name('admin.seller.store-products.toggle-recommended');
Route::delete('store-products/{sellerStoreProduct}', [SellerStoreProductsController::class, 'destroy'])->name('admin.seller.store-products.destroy');
