<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') === 'production' || env('APP_ENV') === 'production') {
            $_SERVER['HTTPS'] = 'on';
            URL::forceScheme('https');
        }
    }
}
