<?php

namespace core\providers;

use core\ServiceContainer;
use core\Response;

    
class ResponseProvider
{
    public function register(ServiceContainer &$container)
    {
        $container->register('module.response', function($request) use ($container) {
            return new Response(
                $request,
                $container                
            );
        });
                        
    }
}