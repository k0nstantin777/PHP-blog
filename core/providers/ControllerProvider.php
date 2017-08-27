<?php

namespace core\providers;

use core\ServiceContainer,
    controller\client\PublicController;

class ControllerProvider
{
    public function register(ServiceContainer &$container)
    {
        $container->register('controller.public', function($request) use ($container) {
            return new PublicController($request, $container);
        });
        
    }
}
