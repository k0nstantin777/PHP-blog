<?php

namespace core\exception;

/**
 * ServiceProviderException
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class ServiceProviderException extends \Exception
{

    public function __construct($message = "CriticalException", $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
            
}

