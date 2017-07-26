<?php

namespace core\exception;

/**
 * Description of AccessException
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class AccessException extends BaseException
{
    public function __construct($message = 'Доступ запрещен', $code = 403, \Exception $previus = null)
    {
        parent::__construct($message, $code, $previus);
    }
}
