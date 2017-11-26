<?php

namespace core\providers;

use core\ServiceContainer;
use core\router\RouteParser;
use core\router\Router;
    
class RouterProvider
{
    public function register(ServiceContainer &$container)
    {
        $container->register('module.router', function($response) {
            return new Router(
                new RouteParser (),
                $response
            );
        });
                        
    }
}