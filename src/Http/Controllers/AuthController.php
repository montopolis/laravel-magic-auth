<?php

namespace Montopolis\MagicAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Montopolis\MagicAuth\Http\Requests\PostCreateRequest;
use Montopolis\MagicAuth\Http\Requests\PostVerifyRequest;
use Montopolis\MagicAuth\Services\Auth\AdapterInterface;
use Montopolis\MagicAuth\Services\KeyGenerator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthController extends Controller
{
    /**
     * POST magic-auth/create.
     * 
     * Generates a key that can be used for magic authentication.
     *
     * @param PostCreateRequest $request
     * @param KeyGenerator      $keyGenerator
     *
     * @return mixed
     */
    public function postCreate(PostCreateRequest $request, KeyGenerator $keyGenerator)
    {
        $email = $request->get('email');
        $token = $request->get('_token');

        // @todo: trusted proxies
        $key = $keyGenerator->generate($email, $token, $request->getClientIp());
        
        $this->sendSlackMessage($email, "Your temporary password is *{$key->key}*... You can log in using this temporary password for the next 5 minutes.");

        return response()->json([
            'message' => ['email' => $email],
        ]);
    }

    /**
     * GET magic-auth/login.
     * 
     * Magic authentication via a URL
     * 
     * @param Request      $request
     * @param KeyGenerator $keyGenerator
     */
    public function getLogin(Request $request, KeyGenerator $keyGenerator, AdapterInterface $auth)
    {
        $email = $request->get('email');
        $csrf = $request->get('_token');
        $ip = $request->ip();
        $key = $request->get('key');

        // @todo: generalise this
        $user = $auth->findByEmail($email);

        if ($keyGenerator->authenticate($user ? $email : '', $csrf, $ip, $key)) {
            // login user
            $auth->loginByEmail($email);
            return redirect()->to('/');
        }

        throw new AccessDeniedHttpException;
    }

    /**
     * POST magic-auth/verify.
     * 
     * Magic auth via a OTP (one time password)
     * 
     * @param PostVerifyRequest $request
     * @param KeyGenerator      $keyGenerator
     * @param AdapterInterface  $auth
     */
    public function postVerify(PostVerifyRequest $request, KeyGenerator $keyGenerator, AdapterInterface $auth)
    {
        $email = $request->get('email');
        $token = $request->get('_token');
        $ip = $request->ip();
        $key = $request->get('key');

        if ($keyGenerator->authenticate($email, $token, $ip, $key)) {
            $auth->loginByEmail($email);
            redirect()->to('/');
        } else {
            throw new AccessDeniedHttpException;
        }
    }

    /**
     * Notify via Slack.
     * 
     * @param $email
     * @param $message
     */
    protected function sendSlackMessage($email, $message)
    {
        /** @var \Montopolis\MagicAuth\Integrations\Slack\Client $s */
        $s = app()->make('Montopolis\MagicAuth\Integrations\Slack\Client');
        $member = $s->fetchByEmail($email);
        $s->sendMessage($member->name, $message);
    }
}
