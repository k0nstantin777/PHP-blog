<?php

namespace core\exception;
use core\exception\BaseException;

/**
 * Description of PageNotFoundException
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class PageNotFoundException extends BaseException
{

    public function __construct($message = '404 error', $code = 404, \Exception $previus = null)
    {
        parent::__construct($message, $code, $previus);
    }

}
