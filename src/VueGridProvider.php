<?php

namespace Joesama\VueGrid;

use Illuminate\Support\ServiceProvider;
use Joesama\VueGrid\Services\Grid;

class VueGridProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
         $this->loadViewsFrom(__DIR__.'/resources/views', 'joesama/vuegrid');

         $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/joesama/vuegrid'),
        ]);


        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'courier');

        $this->publishes([
            __DIR__.'/resources/lang' => resource_path('lang/joesama/vuegrid'),
        ]);


        $this->publishes([
            __DIR__.'/resources/config/vuegrid.php' => config_path('vuegrid.php'),
        ]);


        $this->publishes([
            __DIR__.'/public' => public_path('packages/joesama/vuegrid'),
        ], 'public');


        $this->app->singleton('VueGrid', function ($app) {
            return new Grid;
        });

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
