<?php

namespace Montopolis\MagicAuth\Services;

class NotificationManager
{
    public function sendKey($key)
    {
        if (config('montopolis_magic_auth.channel') === 'slack') {
            
            $this->sendSlackKey($key);
            
        } else {
            throw new \InvalidArgumentException('Magic Auth notification channel not supported.');
        }
    }
    
    public function sendSlackKey($key)
    {
        /** @var \Montopolis\MagicAuth\Services\Notification\Slack $slackNotifier */
        $slackNotifier = app()->make('Montopolis\MagicAuth\Services\Notification\Slack');
        $slackNotifier->sendKey($key);
    }
    
    public function sendEmailKey()
    {
        // @todo
    }
}