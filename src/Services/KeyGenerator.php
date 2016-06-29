<?php

namespace Montopolis\MagicAuth\Services;

use Carbon\Carbon;
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
            'key' => $this->generateNewPin(),
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
        /* @var \Montopolis\MagicAuth\Models\Key $keyObject */
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
        return Carbon::now()->addSeconds(config('montopolis_magic_auth.timeout'));
    }

    /**
     * @return mixed
     */
    public function generateNewPin()
    {
        $pin = '';

        $alphabet = $this->generateAlphabet();
        
        while (strlen($pin) < (int) config('montopolis_magic_auth.pin_length')) {
            // note: should look at replacing mt_rand as a possible refactor
            $index = mt_rand(0, strlen($alphabet) - 1);
            $pin .= $alphabet[$index];
        }
        
        return $pin;
    }

    /**
     * @return string
     */
    public function generateAlphabet()
    {
        $style = config('montopolis_magic_auth.pin_style');
        
        if ($style === 'alpha') {
            return 'ABCDEFGHJKLMNPQRSTUXYZ';
        } elseif ($style === 'numeric') {
            return '1234567890';
        }

        // for alphanumeric we use a reduced character set to avoid confusion (e.g: i ~ 1, o ~ 0)
        return 'ABCDEFGHJKLMNPQRSTUXYZ23456789';
    }
}
