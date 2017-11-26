<?php

namespace core\error_handler;

use controller\client\PublicController;
    

/**
 * ErrorHandlerInterface обработчик ошибок
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
interface ErrorHandlerInterface
{

    public function __construct(Logger $logger = null, $dev = true);

    public function handle($ctrl,\Exception $e, $message);
    
}
