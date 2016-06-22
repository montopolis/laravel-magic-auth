<?php

return [
    
    'channel' => 'slack',

    'services' => [
        'slack' => [
            'token' => 'xxxxx',
            'bot_name' => 'montopolis_magic_auth',
            // otp, link or both
            'mode' => 'pin',
        ],
    ],

    /**
     * This is the adapter tha will be used to perform the user sign-in
     */
    'auth_adapter' => Montopolis\MagicAuth\Services\Auth\LaravelAdapter::class,

    /**
     * Lifetime of sign-in token (in seconds)
     */
    'timeout' => 300,
];
