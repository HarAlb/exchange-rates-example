<?php

namespace App\Modules\ExchangeRate\src;

use Illuminate\Support\ServiceProvider;
use function config_path;

class ExchangeRateServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/exchange.php', 'exchange'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/exchange.php' => config_path('exchange.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/exchange.php');
    }
}
