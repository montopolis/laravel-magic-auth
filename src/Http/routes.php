<?php

$controller = Montopolis\MagicAuth\Http\Controllers\AuthController::class;
// @todo: check this is working
$throttleMiddleware = \Illuminate\Routing\Middleware\ThrottleRequests::class;

$router->group([
    'prefix' => 'magic-auth',
    'middleware' => "{$throttleMiddleware}:10,10",
], function (\Illuminate\Routing\Router $router) use ($controller) {
    $router->post('create', "$controller@postCreate");
    $router->post('verify', "$controller@postVerify");
});