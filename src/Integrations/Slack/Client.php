<?php

namespace Montopolis\MagicAuth\Integrations\Slack;

use GuzzleHttp\Client as HttpClient;

class Client
{
    /**
     * @param $email
     * @return bool
     * {#298 ▼
    +"id": "U02AN6YCG"
    +"team_id": "T02AN6YCE"
    +"name": "coreymcmahon"
    +"deleted": false
    +"status": null
    +"color": "9f69e7"
    +"real_name": "Corey McMahon"
    +"tz": "Asia/Bangkok"
    +"tz_label": "Indochina Time"
    +"tz_offset": 25200
    +"profile": {#285 ▼
        +"first_name": "Corey"
        +"last_name": "McMahon"
        +"skype": "corey_mcmahon"
        +"title": "Founder"
        +"phone": "+85281927442"
        +"avatar_hash": "g87ae7cb8e1d"
        +"real_name": "Corey McMahon"
        +"real_name_normalized": "Corey McMahon"
        +"email": "contact@coreymcmahon.com"
        +"image_24": "https://secure.gravatar.com/avatar/87ae7cb8e1dc49819787951a8a20be30.jpg?s=24&d=https%3A%2F%2Fa.slack-edge.com%2F66f9%2Fimg%2Favatars%2Fava_0012-24.png"
        +"image_32": "https://secure.gravatar.com/avatar/87ae7cb8e1dc49819787951a8a20be30.jpg?s=32&d=https%3A%2F%2Fa.slack-edge.com%2F66f9%2Fimg%2Favatars%2Fava_0012-32.png"
        +"image_48": "https://secure.gravatar.com/avatar/87ae7cb8e1dc49819787951a8a20be30.jpg?s=48&d=https%3A%2F%2Fa.slack-edge.com%2F66f9%2Fimg%2Favatars%2Fava_0012-48.png"
        +"image_72": "https://secure.gravatar.com/avatar/87ae7cb8e1dc49819787951a8a20be30.jpg?s=72&d=https%3A%2F%2Fa.slack-edge.com%2F66f9%2Fimg%2Favatars%2Fava_0012-72.png"
        +"image_192": "https://secure.gravatar.com/avatar/87ae7cb8e1dc49819787951a8a20be30.jpg?s=192&d=https%3A%2F%2Fa.slack-edge.com%2F7fa9%2Fimg%2Favatars%2Fava_0012-192.png"
        +"image_512": "https://secure.gravatar.com/avatar/87ae7cb8e1dc49819787951a8a20be30.jpg?s=512&d=https%3A%2F%2Fa.slack-edge.com%2F7fa9%2Fimg%2Favatars%2Fava_0012-512.png"
    }
    +"is_admin": true
    +"is_owner": true
    +"is_primary_owner": true
    +"is_restricted": false
    +"is_ultra_restricted": false
    +"is_bot": false
    +"has_2fa": false
    }
     */

    /**
     * @param $email
     * @return bool|stdClass
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
        $username = "username=montopolis_magic_auth";

        $client = new HttpClient();
        json_decode($client->get("{$endpoint}&{$channel}&{$text}&{$username}")->getBody());

        return true; // no-op
    }
}
