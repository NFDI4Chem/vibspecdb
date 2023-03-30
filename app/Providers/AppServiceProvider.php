<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

use Inertia\Inertia;

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
            // $this->app['request']->server->set('HTTPS', 'on');
        }

        Inertia::share([
            'urlPrev'	=> function() {
                if (url()->previous() !== route('login') && url()->previous() !== '' && url()->previous() !== url()->current()) {
                    return url()->previous();
                }else {
                    return 'empty'; // used in javascript to disable back button behavior
                }
            },
            'urlFull' => url()->full(),
            'urlCurrent' => url()->current(),
         ]);
        
    }
}
