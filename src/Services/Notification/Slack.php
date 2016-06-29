<?php namespace Montopolis\MagicAuth\Services\Auth;

use Montopolis\MagicAuth\Integrations\Slack\Client;

class Slack
{
    protected $client;

    /**
     * Slack constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $key
     */
    public function sendKey($key)
    {
        $mode = config('montopolis_magic_auth.mode');
        $message = view("montopolis_magic_auth::messages.{$mode}", compact('key'));
        
        $member = $this->findSlackMember($key->email);
        
        $this->sendSlackMessage($member, $message);
    }

    /**
     * Notify via Slack.
     *
     * @param $email
     * @return bool|\Montopolis\MagicAuth\Integrations\Slack\stdClass
     */
    protected function findSlackMember($email)
    {
        return $this->client->fetchByEmail($email);
    }

    /**
     * Notify via Slack.
     *
     * @param $member
     * @param $message
     */
    protected function sendSlackMessage($member, $message)
    {
        $this->client->sendMessage($member->name, $message);
    }
}