<?php

namespace core\providers;

use core\ServiceContainer,
    core\error_handler\ErrorHandler,
    core\error_handler\Logger;

class ErrorHandlerProvider
{
    public function register(ServiceContainer &$container)
    {
        
        $container->register('errorHandler.logger', function($response)  {
            return new ErrorHandler(
                            $response,
                            new Logger('critical', LOG_DIR),
                            DEVELOP
                    );
        });
        
        $container->register('errorHandler.screen', function($response)  {
            return new ErrorHandler(
                            $response,
                            null, 
                            DEVELOP
                    );
        });
    }
}

