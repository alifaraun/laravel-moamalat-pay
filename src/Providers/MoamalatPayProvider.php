<?php

namespace MoamalatPay\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use MoamalatPay\Pay;
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
        $this->loadViewsFrom(__DIR__ . '/../views', 'moamalat-pay');
        Blade::component('moamalat-pay', PayComponent::class);
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . '/../config/moamalat-pay.php' => config_path('moamalat-pay.php'),
        ], 'moamalat-pay');

        $this->mergeConfigFrom(__DIR__ . '/../config/moamalat-pay.php', 'moamalat-pay');


        $this->app->singleton('moamalat-pay', function ($app) {
            return new Pay();
        });
    }
}
