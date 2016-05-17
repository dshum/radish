<?php

namespace Moonlight;

use Illuminate\Support\ServiceProvider;

class MoonlightServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'moonlight');
        
        $this->publishes([
            __DIR__.'/database/migrations' => $this->app->databasePath().'/migrations',
            __DIR__.'/database/seeds' => $this->app->databasePath().'/seeds',
            __DIR__.'/resources/assets' => public_path('packages/moonlight/touch'),
        ]);
        
        include __DIR__.'/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
