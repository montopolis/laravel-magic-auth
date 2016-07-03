<?php

$controller = Montopolis\MagicAuth\Http\Controllers\AuthController::class;

$router->group([
    'prefix' => 'magic-auth',
], function (\Illuminate\Routing\Router $router) use ($controller) {
    
    $throttleMiddleware = \Illuminate\Routing\Middleware\ThrottleRequests::class;
    
    $router->post('create', ['middleware' => "{$throttleMiddleware}:10,5", 'uses' => "$controller@postCreate"]);
    $router->get('login', "$controller@getLogin");
    $router->post('verify', "$controller@postVerify");
});
