<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\Sales\BookingController;
use Webkul\SuperAdmin\Http\Controllers\Sales\CartController;
use Webkul\SuperAdmin\Http\Controllers\Sales\InvoiceController;
use Webkul\SuperAdmin\Http\Controllers\Sales\OrderController;
use Webkul\SuperAdmin\Http\Controllers\Sales\RefundController;
use Webkul\SuperAdmin\Http\Controllers\Sales\SellerWalletRequestController;
use Webkul\SuperAdmin\Http\Controllers\Sales\ShipmentController;
use Webkul\SuperAdmin\Http\Controllers\Sales\TransactionController;

/**
 * Sales routes.
 */
Route::prefix('sales')->group(function () {
    /**
     * Invoices routes.
     */
    Route::controller(InvoiceController::class)->prefix('invoices')->group(function () {
        Route::get('', 'index')->name('superadmin.sales.invoices.index');

        Route::post('create/{order_id}', 'store')->name('superadmin.sales.invoices.store');

        Route::get('view/{id}', 'view')->name('superadmin.sales.invoices.view');

        Route::post('send-duplicate-email/{id}', 'sendDuplicateEmail')->name('superadmin.sales.invoices.send_duplicate_email');

        Route::get('print/{id}', 'printInvoice')->name('superadmin.sales.invoices.print');

        Route::post('mass-update/state', 'massUpdateState')->name('superadmin.sales.invoices.mass_update.state');
    });

    /**
     * Orders routes.
     */
    Route::controller(OrderController::class)->prefix('orders')->group(function () {
        Route::get('', 'landing')->name('superadmin.sales.orders.landing');

        Route::get('customers', 'index')->name('superadmin.sales.orders.customers.index');

        Route::get('create/{cartId}', 'create')->name('superadmin.sales.orders.create');

        Route::post('create/{cartId}', 'store')->name('superadmin.sales.orders.store');

        Route::get('view/{id}', 'view')->name('superadmin.sales.orders.view');

        Route::post('cancel/{id}', 'cancel')->name('superadmin.sales.orders.cancel');

        Route::post('approve/{id}', 'approve')->name('superadmin.sales.orders.approve');

        Route::post('assign-seller/{id}', 'assignSeller')->name('superadmin.sales.orders.assign_seller');

        Route::get('reorder/{id}', 'reorder')->name('superadmin.sales.orders.reorder');

        Route::post('comment/{order_id}', 'comment')->name('superadmin.sales.orders.comment');

        Route::get('search', 'search')->name('superadmin.sales.orders.search');
    });

    /**
     * Refunds routes.
     */
    Route::controller(RefundController::class)->prefix('refunds')->group(function () {
        Route::get('', 'index')->name('superadmin.sales.refunds.index');

        Route::post('create/{order_id}', 'store')->name('superadmin.sales.refunds.store');

        Route::post('update-totals/{order_id}', 'updateTotals')->name('superadmin.sales.refunds.update_totals');

        Route::get('view/{id}', 'view')->name('superadmin.sales.refunds.view');
    });

    /**
     * Shipments routes.
     */
    Route::controller(ShipmentController::class)->prefix('shipments')->group(function () {
        Route::get('', 'index')->name('superadmin.sales.shipments.index');

        Route::post('create/{order_id}', 'store')->name('superadmin.sales.shipments.store');

        Route::get('view/{id}', 'view')->name('superadmin.sales.shipments.view');
    });

    /**
     * Transactions routes.
     */
    Route::controller(TransactionController::class)->prefix('transactions')->group(function () {
        Route::get('', 'index')->name('superadmin.sales.transactions.index');

        Route::post('create', 'store')->name('superadmin.sales.transactions.store');

        Route::get('view/{id}', 'view')->name('superadmin.sales.transactions.view');
    });

    /**
     * Seller wallet deposit / withdraw requests (approve from Super Admin).
     */
    Route::controller(SellerWalletRequestController::class)->prefix('wallet-requests')->group(function () {
        Route::get('', 'index')->name('superadmin.sales.wallet-requests.index');

        Route::post('{transaction}/approve', 'approve')->name('superadmin.sales.wallet-requests.approve');

        Route::post('{transaction}/reject', 'reject')->name('superadmin.sales.wallet-requests.reject');
    });

    Route::controller(CartController::class)->prefix('cart')->group(function () {
        Route::get('{id}', 'index')->name('superadmin.sales.cart.index');

        Route::post('create', 'store')->name('superadmin.sales.cart.store');

        Route::post('{id}/items', 'storeItem')->name('superadmin.sales.cart.items.store');

        Route::put('{id}/items', 'updateItem')->name('superadmin.sales.cart.items.update');

        Route::delete('{id}/items', 'destroyItem')->name('superadmin.sales.cart.items.destroy');

        Route::post('{id}/addresses', 'storeAddress')->name('superadmin.sales.cart.addresses.store');

        Route::post('{id}/shipping-methods', 'storeShippingMethod')->name('superadmin.sales.cart.shipping_methods.store');

        Route::post('{id}/payment-methods', 'storePaymentMethod')->name('superadmin.sales.cart.payment_methods.store');

        Route::post('{id}/coupon', 'storeCoupon')->name('superadmin.sales.cart.store_coupon');

        Route::delete('{id}/coupon', 'destroyCoupon')->name('superadmin.sales.cart.remove_coupon');
    });

    Route::controller(BookingController::class)->prefix('bookings')->group(function () {
        Route::get('', 'index')->name('superadmin.sales.bookings.index');

        Route::get('get', 'get')->name('superadmin.sales.bookings.get');
    });
});
