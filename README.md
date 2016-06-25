# Laravel Magic Auth package

Package for implementing Slack-style "magic auth" links in Laravel

## Set up

### Install the package via composer

```
$ composer require montopolis/laravel-magic-auth
```

### Add the service provider

In config/app.php inside the providers array...

```
    /*
     * Application Service Providers...
     */
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,

    /*
     * Third-party Service Providers...
     */
    Montopolis\MagicAuth\Providers\ServiceProvider::class,
```

### Publish (and update) configuration

```
$ php artisan vendor:publish
$ vi config/montopolis_magic_auth.php
```

### License

Laravel Magic Auth is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
