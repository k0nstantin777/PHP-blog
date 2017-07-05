<?php

namespace core\exception;

/**
 * Description of ValidatorException
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class ValidatorException extends BaseException
{
    public function __construct($message = 'Error', $code = 200, \Exception $previus = null)
    {
        parent::__construct($message, $code, $previus);
        
    }
}
