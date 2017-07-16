<?php

namespace core\exception;

/**
 * Description of PostException
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class PostException extends BaseException
{
    public function __construct($message = '', $code = 403, \Exception $previus = null)
    {
        parent::__construct($message, $code, $previus);
    }
    
}
