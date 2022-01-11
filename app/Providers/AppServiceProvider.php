<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            // Force Https for assets
            URL::forceScheme('https');
            // Force Https for signed URLs, verify email for example:
            $this->app['request']->server->set('HTTPS', 'on');
        }
    }
}
