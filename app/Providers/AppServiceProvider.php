<?php

namespace App\Providers;

use App\Auth\HashedEmailUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::provider('hashed_email', function ($app, array $config) {
            return new HashedEmailUserProvider($app['hash'], $config['model']);
        });
    }
}
