<?php

namespace core\providers;

use core\ServiceContainer,
    controller\BaseController;

class ControllerProvider
{
    public function register(ServiceContainer &$container)
    {
        $container->register('controller.base', function($request) use ($container) {
            return new BaseController($request, $container);
        });
        
    }
}

//new BaseController($request, $container)