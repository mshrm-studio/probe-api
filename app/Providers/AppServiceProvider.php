<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Web3::class, function ($app) {
            return new Web3(new HttpProvider(new HttpRequestManager(config('services.infura.url'), 2)));

            // return new Web3(config('services.infura.url'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
