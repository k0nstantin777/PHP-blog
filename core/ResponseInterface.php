<?php

namespace core;

/**
 * Ответ клиенту
 * @author bumer
 */
interface ResponseInterface
{  
    public function getController (string $controller);
    
    public function getAction (string $action);
    
    public function setGetParams (array $params);
        
    /**
     * Установка заголовков ответа сервера
     * 
     * @return string
     */
    public function setHeader(string $header);
    
            
    /**
     * Отправка ответа клиенту
     *  
     * @return string
     */
    public function send($controller = DEFAULT_CONTROLLER, $action = '', array $get_params = [], array $action_params = [], $header = 'HTTP/1.1 200 Ok');
}
