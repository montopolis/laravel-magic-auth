<?php namespace Montopolis\MagicAuth\Services\Auth;

/**
 * Class LaravelAdapter
 * 
 * Implements log-in for standard Laravel auth system.
 * 
 * @package Montopolis\MagicAuth\Services\Auth
 */
class LaravelAdapter implements AdapterInterface
{
    /**
     * @param $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        $class = app()->config['auth.providers.users.model'];
        $user = new $class;
        return $user->where('email', $email)->first();
    }

    /**
     * @param string $email
     * @return bool
     */
    public function loginByEmail($email)
    {
        $user = $this->findByEmail($email);
        
        if ($user) {
            app()->make('guard')->setUser($user);
            return true;
        }
        
        return false;
    }
}