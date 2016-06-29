You can log-in automatically by clicking the following link:

{{
    url('magic-auth/login') . '?' . implode('&', [
        "email={$key->email}",
        "_token={$key->token}",
        "key={$key->key}",
    ])
}}

You can log in using this temporary link for the next 5 minutes.