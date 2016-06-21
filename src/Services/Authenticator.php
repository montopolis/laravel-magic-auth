<?php namespace Montopolis\MagicAuth\Services;


class Authenticator
{
    public function login($email)
    {
        /** @var \Cartalyst\Sentinel\Sentinel $s */
        $s = app()->make('Cartalyst\Sentinel\Sentinel');
        $class = config('auth.providers.users.model');
        $u = new $class;
        $u = $u->where('email', $email)->first();
        $u = $s->getUserRepository()->findById($u->id);
        $s->login($u);
    }
}