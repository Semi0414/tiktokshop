<?php

use Illuminate\Support\Facades\Route;
use Webkul\Core\Http\Middleware\NoCacheMiddleware;

/**
 * Auth routes.
 */
require 'auth-routes.php';

Route::group(['middleware' => ['superadmin:superadmin', NoCacheMiddleware::class], 'prefix' => config('app.super_admin_url')], function () {
    /**
     * Sales routes.
     */
    require 'sales-routes.php';

    /**
     * Catalog routes.
     */
    require 'catalog-routes.php';

    /**
     * Customers routes.
     */
    require 'customers-routes.php';

    /**
     * Sellers routes.
     */
    require 'sellers-routes.php';

    /**
     * Marketing routes.
     */
    require 'marketing-routes.php';

    /**
     * CMS routes.
     */
    require 'cms-routes.php';

    /**
     * Landing / home page hero media.
     */
    require 'home-page-media-routes.php';

    /**
     * Reporting routes.
     */
    require 'reporting-routes.php';

    /**
     * Settings routes.
     */
    require 'settings-routes.php';

    /**
     * Configuration routes.
     */
    require 'configuration-routes.php';

    /**
     * Notification routes.
     */
    require 'notification-routes.php';

    /**
     * Remaining routes.
     */
    require 'rest-routes.php';
});
