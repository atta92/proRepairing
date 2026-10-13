<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\SiteSetting;
use App\Services\ShopCart;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('siteSettings', SiteSetting::current());
        });

        View::composer(['partials.header', 'partials.footer', 'partials.bag-drawer', 'layouts.app', 'home'], function ($view) {
            $view->with(
                'navCategories',
                Category::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get(['id', 'name', 'slug', 'type'])
            );

            $cart = app(ShopCart::class);
            $view->with('shopCartCount', $cart->count());
            $view->with('shopBagPayload', $cart->clientPayload());
        });
    }
}
