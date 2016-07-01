You can log-in automatically by clicking the following link: {{
    url('magic-auth/login') . '?' . implode('&', [
        "email={$key->email}",
        "_token={$key->token}",
        "key={$key->key}",
    ])
}}

This link will work for the next {{ round(config('montopolis_magic_auth.timeout') / 60, 0, PHP_ROUND_HALF_DOWN) }} minutes.