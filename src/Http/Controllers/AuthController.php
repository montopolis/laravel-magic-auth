<?php

namespace Montopolis\MagicAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Montopolis\MagicAuth\Http\Requests\PostCreateRequest;
use Montopolis\MagicAuth\Http\Requests\PostVerifyRequest;
use Montopolis\MagicAuth\Services\Auth\AdapterInterface;
use Montopolis\MagicAuth\Services\KeyGenerator;
use Montopolis\MagicAuth\Services\NotificationManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function postCreate(PostCreateRequest $request, KeyGenerator $keyGenerator, NotificationManager $notifier, AdapterInterface $auth)
    {
        $email = $request->get('email');
        $token = $request->get('_token');

        $member = $this->findSlackMember($email);

        if (!$member) {
            return $this->exception(new NotFoundHttpException("Could not find Slack user for email address: {$email}"));
        }
        
        if (!$auth->findByEmail($email)) {
            return $this->exception(new NotFoundHttpException("Could not find user account for email address: {$email}"));
        }
        
        // @todo: trusted proxies

        // Generate a one time password 
        $key = $keyGenerator->generate($email, $token, $request->getClientIp());
        
        $notifier->sendKey($key);

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
        if (!in_array(config('montopolis_magic_auth.mode'), ['link', 'both'])) {
            return $this->exception(new AccessDeniedHttpException('"link" authentication mode is not enabled'));
        }

        $email = $request->get('email');
        $csrf = $request->get('_token');
        $ip = $request->ip();
        $key = $request->get('key');

        // @todo: generalise this
        $user = $auth->findByEmail($email);

        if ($user && $keyGenerator->authenticate($email, $csrf, $ip, $key)) {
            // login user
            $auth->loginByEmail($email);
            return redirect()->to('/');
        }

        return $this->exception(new AccessDeniedHttpException);
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
        if (!in_array(config('montopolis_magic_auth.mode'), ['otp', 'both'])) {
            return $this->exception(new AccessDeniedHttpException('"otp" authentication mode is not enabled'));
        }

        $email = $request->get('email');
        $token = $request->get('_token');
        $ip = $request->ip();
        $key = $request->get('key');

        if ($keyGenerator->authenticate($email, $token, $ip, $key)) {
            $auth->loginByEmail($email);
            redirect()->to('/');
        } else {
            return $this->exception(new AccessDeniedHttpException);
        }
    }

    /**
     * Notify via Slack.
     *
     * @param $email
     * @return bool|\Montopolis\MagicAuth\Integrations\Slack\stdClass
     */
    protected function findSlackMember($email)
    {
        /** @var \Montopolis\MagicAuth\Integrations\Slack\Client $s */
        $s = app()->make('Montopolis\MagicAuth\Integrations\Slack\Client');
        $member = $s->fetchByEmail($email);
        return $member;
    }

    /**
     * @param $exception \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Exception
     */
    protected function exception($exception)
    {
        if (request()->isJson()) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }
        
        throw $exception;
    }
}
