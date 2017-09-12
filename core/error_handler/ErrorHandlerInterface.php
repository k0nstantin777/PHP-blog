<?php

namespace core\error_handler;

use core\ResponseInterface;
    

/**
 * ErrorHandlerInterface обработчик ошибок
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
interface ErrorHandlerInterface
{

    public function __construct(ResponseInterface $response, Logger $logger = null, $dev = true);

    public function handle(\Exception $e, $message);
    
}
