<?php

namespace core\exception;

use core\exception\BaseException;

/**
 * ModelException
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class ModelException extends BaseException
{
    public function __construct($message = "CriticalException", $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
