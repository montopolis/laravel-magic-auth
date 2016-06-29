<?php

namespace Montopolis\MagicAuth\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    protected $table = 'montopolis_magic_auth_keys';

    protected $fillable = [
        'email',
        'token',
        'ip_address',
        'key',
        'expires_at',
        'is_valid',
        'attempts',
    ];

    protected $hidden = [
        'key',
    ];

    /**
     * Marks the key as invalid.
     *
     * @return $this
     */
    public function invalidate()
    {
        $this->update([
            'is_valid' => false,
        ]);

        return $this;
    }

    /**
     * Attempts to authenticate with the given token and key.
     * 
     * @param $csrfToken
     * @param $key
     *
     * @return bool
     */
    public function attempt($csrfToken, $key, $ipAddress)
    {
        $this->incrementAttempts();

        if ($this->isExpired()) {
            $this->invalidate();

            return false;
        }

        // test the auth conditions
        $valid = ($this->ip_address === $ipAddress) &&
            ($this->token === $csrfToken) &&
            ($this->key === $key);
        
        // if valid, revoke the token
        if ($valid) {
            $this->invalidate();
        }
        
        return $valid;
    }

    /**
     * Determine if the key is invalid, based on validity parameter, number of attempts and expiry timestamp.
     *
     * @return bool
     */
    public function isExpired()
    {
        if (!$this->is_valid) {
            return true;
        }

        if ($this->attempts > 3) {
            return true;
        }

        if (Carbon::now() > $this->expires_at) {
            return true;
        }
    }

    /**
     * Increment the number of authentication attempts.
     *
     * @return $this
     */
    public function incrementAttempts()
    {
        $this->update([
            'attempts' => ((int) $this->attempts) + 1,
        ]);
    }
}
