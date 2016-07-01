<?php

namespace Montopolis\MagicAuth\Services;

class NotificationManager
{
    /**
     * Notify the user of their key for magic authentication.
     * 
     * @param $key
     */
    public function sendKey($key)
    {
        if (config('montopolis_magic_auth.channel') === 'slack') {

            $this->sendSlackKey($key);

        } elseif (config('montopolis_magic_auth.channel') === 'email') {
            
            $this->sendEmailKey($key);
            
        } else {
            throw new \InvalidArgumentException('Magic Auth notification channel not supported.');
        }
    }

    /**
     * @param $key
     */
    public function sendSlackKey($key)
    {
        /** @var \Montopolis\MagicAuth\Services\Notification\Slack $slackNotifier */
        $slackNotifier = app()->make('Montopolis\MagicAuth\Services\Notification\Slack');
        $slackNotifier->sendKey($key);
    }

    /**
     * @param $key
     */
    public function sendEmailKey($key)
    {
        /** @var \Montopolis\MagicAuth\Services\Notification\Mail $mailNotifier */
        $mailNotifier = app()->make('Montopolis\MagicAuth\Services\Notification\Mail');
        $mailNotifier->sendKey($key);
    }
}