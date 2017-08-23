<?php

namespace core\providers;

use core\ServiceContainer,
    core\module\User;

class UserProvider
{
    public function register(ServiceContainer &$container)
    {
        $container->register('service.user', function($request) use ($container) {
            return new User(
                $request,
                $container->get('model.user'),
                $container->get('model.session'),
                $container->get('model.priv')
            );
        });
                        
    }
}