<?php
namespace Joesama\VueGrid;

use Illuminate\Foundation\AliasLoader;
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
        $path = realpath(__DIR__.'/../');

        $this->loadViewsFrom($path.'/resources/views', 'joesama/vuegrid');

        $this->publishes([
            $path.'/resources/views' => resource_path('views/joesama/vuegrid'),
        ]);


        $this->loadTranslationsFrom($path.'/resources/lang', 'joesama/vuegrid');

        $this->publishes([
            $path.'/resources/lang' => resource_path('lang/joesama/vuegrid'),
        ]);


        $this->publishes([
            $path.'/resources/config/vuegrid.php' => config_path('vuegrid.php'),
        ]);


        $this->publishes([
            $path.'/resources/public' => public_path('packages/joesama/vuegrid'),
        ], 'public');

        if (class_exists('Illuminate\Foundation\AliasLoader')) {
            AliasLoader::getInstance()->alias(
                'VueGrid',
                Services\Grid::class
            );
        } else {
            class_alias(Services\Grid::class, 'VueGrid');
        }


    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('VueGrid', function ($app) {
            return new Grid;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['VueGrid'];
    }

}
