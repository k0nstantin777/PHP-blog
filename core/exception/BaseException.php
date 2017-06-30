<?php

namespace core\exception;

/**
 * Description of BaseException
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class BaseException extends \Exception
{

    public function __construct($message = "CriticalException", $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    
    public function errorSend ()
    {
        nl2br(
          'Filed connect to DB' . PHP_EOL
        . 'Message: ' . $this->getMessage() . PHP_EOL
        . 'Trace: ' . PHP_EOL . $this->getTraceAsString()
        );
    }

}
