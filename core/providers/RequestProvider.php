<?php

namespace core\providers;

use core\ServiceContainer,
    core\Request,
    core\helper\Cookie,
    core\helper\Session;

class RequestProvider
{
	public function register(ServiceContainer &$container)
	{
            $container->register('request', function() {
                    return new Request(
                            $_GET,
                            $_POST,
                            $_FILES,
                            new Cookie(),
                            $_SERVER,
                            new Session()
                    );
            });
            
        }
}

