<?php namespace Montopolis\MagicAuth\Services\Notification;

class Mail
{
    /**
     * @param $key \Montopolis\MagicAuth\Models\Key
     */
    public function sendKey($key)
    {
        $mode = config('montopolis_magic_auth.mode');
        $message = view("montopolis_magic_auth::messages.{$mode}", compact('key'));

        $email = $key->email;

        Mail::raw($message, function ($message) use ($email) {
            $domain = url('/');
            $parts = explode('://', $domain);

            $message->to($email)
                ->subject('Sign in for ' . $parts[1]);
        });
    }
}