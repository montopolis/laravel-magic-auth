<?php namespace Montopolis\MagicAuth\Services\Auth;

/**
 * Class SentinelAdapter
 * 
 * Example implementation for a third-party auth provider.
 * 
 * @package Montopolis\MagicAuth\Services\Auth
 */
class SentinelAdapter implements AdapterInterface
{
    /**
     * @param $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        $class = config('auth.providers.users.model');
        $u = new $class();
        return $u->where('email', $email)->first();
    }

    /**
     * @param $email
     * @return mixed|void
     */
    public function loginByEmail($email)
    {
        $user = $this->findByEmail($email);
        
        /** @var \Cartalyst\Sentinel\Sentinel $s */
        $sentinel = app()->make('Cartalyst\Sentinel\Sentinel');
        
        $sentinelUser = $sentinel->getUserRepository()->findById($user->id);
        $s->login($sentinelUser);
        return true;
    }
}