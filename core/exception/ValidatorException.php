<?php

namespace core\exception;

/**
 * ValidatorException
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class ValidatorException extends BaseException
{
    private $errors;
    
    public function __construct($errors =[], $message = 'Error', $code = 200, \Exception $previus = null)
    {
        parent::__construct($message, $code, $previus);
        $this->errors = $errors;
    }
    
    public function getErrors ()
    {
        return $this->errors;
    }
}
