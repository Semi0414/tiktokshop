<?php

namespace Webkul\SuperAdmin\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance;
use Webkul\SuperAdmin\Services\AdminReferralCodeService;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->app->singleton(AdminReferralCodeService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::middleware(['web', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'superadmin');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'superadmin');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'superadmin');

        View::composer('superadmin::*', static function ($view) {
            $data = $view->getData();

            if (! array_key_exists('datagridPayload', $data)) {
                $view->with('datagridPayload', null);
            }
        });

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.superadmin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php',
            'acl'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/superadmin.php',
            'superadmin'
        );
    }
}
