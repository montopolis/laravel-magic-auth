<?php

return [
    // may support email, sms in future
    'channel' => 'slack',

    'services' => [
        'slack' => [
            'token' => 'xxxxx',
            'bot_name' => 'montopolis_magic_auth',
            // otp, link or both
            'mode' => 'pin',
        ],
    ],
];
