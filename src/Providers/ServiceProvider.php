<?php

namespace Montopolis\MagicAuth\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    const CONFIG_PATH = '../config/montopolis_magic_auth.php';

    public function boot()
    {
        // define routes
        if (!$this->app->routesAreCached()) {
            // define router as it will be used by the routes file
            $router = $this->app->router;
            require __DIR__.'/../Http/routes.php';
        }

        // publish config file (if relevant)
        $key = __DIR__.self::CONFIG_PATH;
        $this->publishes([
            "{$key}" => config_path('montopolis_magic_auth.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.self::CONFIG_PATH, 'courier'
        );
    }
}
