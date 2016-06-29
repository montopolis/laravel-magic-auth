<?php

return [
    
    /**
     * OTP delivery mechanism: slack, email, or both
     */
    'channel' => 'slack',

    /**
     * OTP usage mechanism: otp, link or both
     */
    'mode' => 'both',

    /**
     * PIN (OTP) parameters. Style can be: alpha, numeric, or alphanumeric
     */
    'pin_length' => 6,
    'pin_style' => 'alphanumeric',

    /**
     * This is the adapter that will be used to perform the user sign-in. Must implement this interface:
     *      Montopolis\MagicAuth\Services\Auth\AdapterInterface
     */
    'auth_adapter' => Montopolis\MagicAuth\Services\Auth\LaravelAdapter::class,

    /**
     * Lifetime of sign-in token (in seconds)
     */
    'timeout' => 300,

    /**
     * Notification channel(s).
     *      TOKEN(S) MUST BE SUPPLIED!
     */
    'services' => [

        'slack' => [
            'token' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
            'bot_name' => 'montopolis_magic_auth',
        ],
    ],
];
