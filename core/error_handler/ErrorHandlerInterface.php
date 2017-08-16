<?php

namespace core\error_handler;

use controller\BaseController;
    

/**
 * ErrorHandlerInterface обработчик ошибок
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
interface ErrorHandlerInterface
{

    public function __construct(BaseController $ctrl, Logger $logger = null, $dev = true);

    public function handle(\Exception $e, $message);
    
}
