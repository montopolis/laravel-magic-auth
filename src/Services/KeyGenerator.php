<?php

namespace Montopolis\MagicAuth\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Montopolis\MagicAuth\Models\Key;

class KeyGenerator
{
    /**
     * @param $email
     * @param $csrfToken
     *
     * @return static
     */
    public function generate($email, $csrfToken, $ipAddress)
    {
        // invalidate all prior keys for this email
        Key::where('email', $email)
            ->update([
                'is_valid' => 0,
            ]);

        $key = Key::create([
            'email' => $email,
            'token' => $csrfToken,
            'ip_address' => $ipAddress,
            'key' => strtoupper(Str::random(6)),
            'expires_at' => $this->getExpiry(),
            'is_valid' => 1,
        ]);

        return $key;
    }

    /**
     * @param $email
     * @param $csrfToken
     * @param $key
     *
     * @return bool|string Will return the email address of the authenticated user (or FALSE if none)
     */
    public function authenticate($email, $csrfToken, $ipAddress, $key)
    {
        /* @var \Montopolis\MagicAuth\Models\Key $key */
        $keyObject = Key::where('email', $email)
            ->where('is_valid', 1)
            ->orderBy('created_at', 'DESC')
            ->first();

        if ($keyObject->attempt($csrfToken, $key, $ipAddress)) {
            return $email;
        } else {
            return false;
        }
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function generateUrl($key)
    {
        $parts = [
            "email={$key->email}",
            "_token={$key->token}",
            "key={$key->key}",
        ];

        return url('magic-auth/login?'.implode('&', $parts));
    }

    /**
     * @return static
     */
    public function getExpiry()
    {
        // @todo: extract as a config var?
        return Carbon::now()->addMinutes(5);
    }
}
