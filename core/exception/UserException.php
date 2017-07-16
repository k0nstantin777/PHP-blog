<?php

namespace core\exception;

/**
 * Исключения UserException
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class UserException extends BaseException
{
    private $errors;
    
    public function __construct(array $errors =[], $message = '', $code = 403, \Exception $previus = null)
    {
        parent::__construct($message, $code, $previus);
        $this->errors = $errors;
    }
    
    public function getErrors ()
    {
        return $this->errors;
    }
}
