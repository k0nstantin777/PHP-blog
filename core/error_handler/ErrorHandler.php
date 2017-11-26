<?php

namespace core\error_handler;

use core\ResponseInterface;
  

/**
 * Description of ErrorHandler
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class ErrorHandler implements ErrorHandlerInterface
{

    private $logger;
    private $dev;

    public function __construct(ResponseInterface $response, Logger $logger = null, $dev = true)
    {
        $this->logger = $logger;
        $this->dev = $dev;
        $this->response = $response;
    }

    /**
     * Обработка вывода ошибки
     * @param \Exception $e
     * @param type $message
     */
    public function handle(\Exception $e, $message)
    {
        if (isset($this->logger)) {
            $this->logger->write(sprintf("%s\n%s", $e->getMessage(), $e->getTraceAsString()), 'ERROR');
        }

        $msg = sprintf('<h1>%s</h1>', $message);

        if ($this->dev) {
            $msg = sprintf('%s<h2>%s</h2><p>%s</p>', $msg, $e->getMessage(), $e->getTraceAsString());
        }
        
        $this->response->send('page', 'error', [], [$msg, $e->getMessage()], $this->setHeaderByCode($e->getCode()));
    }
    
    /**
     * Установка ответа сервера по коду исключения
     * @param type $code
     * @return string
     */
    private function setHeaderByCode($code)
    {
        switch ($code){
            case 404: $header = "HTTP/1.1 404 Not Found"; break;
            case 403: $header = "HTTP/1.1 403 Forbidden"; break;
            case 500: $header = "HTTP/1.1 500"; break;
        }
        
        return $header;
    }

}
