<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//CorsMiddleware.php

namespace app\Middleware;

class CorsMiddleware
{
    public function __invoke(\Slim\Http\Request $request, \Slim\Http\Response $response, $next) {

        /** @var $response \Slim\Http\Response */
        $response = $next($request, $response);

        return $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

    }
}