<?php
use Slim\Http\Request;
use Slim\Http\Response;
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
// CORS Preflight middleware
$app->add(function (Request $request, Response $response, $next) {
    if($request->getMethod() !== 'OPTIONS') {
        return $next($request, $response);
    }

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    $response = $response->withHeader('Access-Control-Allow-Methods', $request->getHeaderLine('Access-Control-Request-Method'));
    $response = $response->withHeader('Access-Control-Allow-Headers', $request->getHeaderLine('Access-Control-Request-Headers'));

    return $next($request, $response);
});

