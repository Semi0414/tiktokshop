<?php

use Illuminate\Support\Facades\Route;
use Webkul\SuperAdmin\Http\Controllers\NotificationController;

/**
 * Notification routes.
 */
Route::controller(NotificationController::class)->group(function () {
    Route::get('notifications', 'index')->name('superadmin.notification.index');

    Route::get('get-notifications', 'getNotifications')->name('superadmin.notification.get_notification');

    Route::get('viewed-notifications/{orderId}', 'viewedNotifications')->name('superadmin.notification.viewed_notification');

    Route::post('read-all-notifications', 'readAllNotifications')->name('superadmin.notification.read_all');
});
