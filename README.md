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

### API

#### Create a token

Use this endpoint to trigger creation of an OTP for the user. The user will be notified via the preselected channel (see `montopolis_magic_auth.php`) with either the password, a magic auth link or both (also see config).

```
POST magic-auth/create
{
    "email": "johnny@depp.com",
    "_token": "ABCDEFghijklmnOPQRStuvwxyz1234567890"
}

200 OK
{
    "message": {
        "email": "johnny@depp.com"
    }
}
```

#### Verify a token

After the OTP has been received and forwarded by the user, you can attempt authentication using this endpoint:

```
POST magic-auth/verify
{
    "email": "johnny@depp.com",
    "_token": "ABCDEFghijklmnOPQRStuvwxyz1234567890",
    "key": "12345"
}

301 Redirect
```

Note: After posting to this endpoint, the Laravel session will be authenticated (if successful).

#### Magic link

The user can also be redirected as such to automatically sign them in:

```
GET magic-auth/login?email=johnny@depp.com&_token=ABCDEFghijklmnOPQRStuvwxyz1234567890&key=12345

301 Redirect
```

### License

Laravel Magic Auth is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
