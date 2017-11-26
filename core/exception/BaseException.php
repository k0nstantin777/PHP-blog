<?php

namespace core\exception;

/**
 * BaseException
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class BaseException extends \Exception
{

    public function __construct($message = "CriticalException", $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    
                
}
