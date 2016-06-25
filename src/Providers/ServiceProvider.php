<?php

namespace Montopolis\MagicAuth\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Montopolis\MagicAuth\Services\Auth\AdapterInterface;

class ServiceProvider extends BaseServiceProvider
{
    /**
     *
     */
    const CONFIG_PATH = '/../../config/montopolis_magic_auth.php';

    /**
     *
     */
    public function boot()
    {
        // Define routes
        $router = $this->app->router;
        require __DIR__.'/../Http/routes.php';

        // Publish config file (if not yet published)
        $path = __DIR__ . self::CONFIG_PATH;
        $this->publishes([
            "{$path}" => config_path('montopolis_magic_auth.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Merge default config with the published config file
        $this->mergeConfigFrom(
            __DIR__.self::CONFIG_PATH, 'montopolis_magic_auth'
        );

        // Bind the adapter interface into the app container based on the config file
        $this->app->bind(AdapterInterface::class, function ($app) {
            $classname = $app->config['montopolis_magic_auth.auth_adapter'];
            return new $classname;
        });
    }
}
