<?php

namespace core\providers;

use core\ServiceContainer,
    core\error_handler\ErrorHandler,
    core\error_handler\Logger,
    controller\BaseController;

class ErrorHandlerProvider
{
    public function register(ServiceContainer &$container)
    {
        
        $container->register('errorHandler.logger', function($request) use ($container) {
            return new ErrorHandler(
                            $container->get('controller.base', [$request]),
                            new Logger('critical', LOG_DIR),
                            DEVELOP
                    );
        });
        
        $container->register('errorHandler.screen', function($request) use ($container)  {
            return new ErrorHandler(
                            $container->get('controller.base', [$request])
                            
                    );
        });
    }
}

