<?php

namespace Montopolis\MagicAuth\Integrations\Slack;

use GuzzleHttp\Client as HttpClient;

class Client
{
    /**
     * @param $email
     * @return bool
     */
    public function fetchByEmail($email)
    {
        $token = config('montopolis_magic_auth.services.slack.token');

        // https://api.slack.com/methods/users.list
        $endpoint = "https://slack.com/api/users.list?token={$token}";

        $client = new HttpClient();
        $users = json_decode($client->get($endpoint)->getBody());

        foreach ($users->members as $member) {
            if ($member->profile->email === $email) {
                return $member;
            }
        }

        return false;
    }

    /**
     * @param $to
     * @param $message
     *
     * @return bool
     */
    public function sendMessage($to, $message)
    {
        $message = urlencode($message);

        $token = config('montopolis_magic_auth.services.slack.token');

        // https://api.slack.com/methods/chat.postMessage
        $endpoint = "https://slack.com/api/chat.postMessage?token={$token}";
        $channel = "channel=@{$to}";
        $text = "text={$message}";
        $username = 'username=montopolis_magic_auth';

        $client = new HttpClient();
        json_decode($client->get("{$endpoint}&{$channel}&{$text}&{$username}")->getBody());

        return true; // no-op
    }
}
