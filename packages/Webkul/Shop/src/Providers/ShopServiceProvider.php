<?php

namespace Webkul\Shop\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance;
use Webkul\Core\Models\HomePageMedia;
use Webkul\Shop\Http\Middleware\AuthenticateCustomer;
use Webkul\Shop\Http\Middleware\CacheResponse;
use Webkul\Shop\Http\Middleware\Currency;
use Webkul\Shop\Http\Middleware\Locale;
use Webkul\Shop\Http\Middleware\ResolveSellerPreviewToken;
use Webkul\Shop\Http\Middleware\Theme;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router): void
    {
        $router->middlewareGroup('shop', [
            ResolveSellerPreviewToken::class,
            Theme::class,
            Locale::class,
            Currency::class,
        ]);

        $router->aliasMiddleware('theme', Theme::class);
        $router->aliasMiddleware('locale', Locale::class);
        $router->aliasMiddleware('currency', Currency::class);
        $router->aliasMiddleware('cache.response', CacheResponse::class);
        $router->aliasMiddleware('customer', AuthenticateCustomer::class);

        Route::middleware(['web', 'shop', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/web.php');
        Route::middleware(['web', 'shop', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'shop');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'shop');

        Paginator::defaultView('shop::partials.pagination');
        Paginator::defaultSimpleView('shop::partials.pagination');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'shop');

        View::composer([
            'shop::landing.index',
            'shop::landing.join',
            'shop::tik-store.index',
            'shop::tik-store.list',
        ], function ($view) {
            $__sfLegacy = (bool) config('storefront-branding.legacy_tiktok_branding');
            $__sfName = (string) config('storefront-branding.marketplace_name');
            $__sfStoreLabel = $__sfLegacy ? 'TikStore' : $__sfName;
            $__sfNameWords = array_values(array_filter(
                preg_split('/\s+/u', $__sfName) ?: [],
                static fn ($s) => $s !== ''
            ));
            if ($__sfLegacy) {
                $__sfLogoMark = 'TS';
            } else {
                $initials = '';
                foreach (array_slice($__sfNameWords, 0, 2) as $w) {
                    $initials .= mb_strtoupper(mb_substr($w, 0, 1));
                }
                $__sfLogoMark = $initials !== '' ? mb_substr($initials, 0, 3) : 'MS';
            }
            $view->with(compact(
                '__sfLegacy',
                '__sfName',
                '__sfStoreLabel',
                '__sfNameWords',
                '__sfLogoMark'
            ));

            if ($view->name() === 'shop::landing.index' && ! array_key_exists('landingHeroMedia', $view->getData())) {
                $view->with(
                    'landingHeroMedia',
                    HomePageMedia::query()
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('id')
                        ->get()
                );
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
            'menu.customer'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/seller.php',
            'seller'
        );
    }
}
