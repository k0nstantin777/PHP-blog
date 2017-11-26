<?php

namespace core\providers;

use core\ServiceContainer,
    core\error_handler\ErrorHandler,
    core\error_handler\Logger;

class ErrorHandlerProvider
{
    public function register(ServiceContainer &$container)
    {
        
        $container->register('errorHandler.logger', function()  {
            return new ErrorHandler(
                            new Logger('critical', LOG_DIR),
                            DEVELOP
                    );
        });
        
        $container->register('errorHandler.screen', function()  {
            return new ErrorHandler(
                            null, 
                            DEVELOP
                    );
        });
    }
}

