<?php

namespace MoamalatPay\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use MoamalatPay\Http\Middleware\AllowedIps;
use MoamalatPay\Pay;
use MoamalatPay\Refund;
use MoamalatPay\View\Components\Pay as PayComponent;

class MoamalatPayProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'moamalat-pay');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadFactoriesFrom(__DIR__.'/../database/factories');
        Blade::component('moamalat-pay', PayComponent::class);

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('moamalat-allowed-ips', AllowedIps::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/../config/moamalat-pay.php' => config_path('moamalat-pay.php'),
        ], 'moamalat-pay');

        $this->mergeConfigFrom(__DIR__.'/../config/moamalat-pay.php', 'moamalat-pay');

        $this->app->singleton('moamalat-pay', function ($app) {
            return new Pay;
        });

        $this->app->singleton('moamalat-pay-refund', function ($app) {
            return new Refund;
        });
    }
}
