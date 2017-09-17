<?php

namespace core;

/**
 * Ответ клиенту
 * @author bumer
 */
class Response implements ResponseInterface
{  
    public $controller;
    public $action;
    public $request;
    public $conteiner;
    
    public function __construct(Request $request, ServiceContainer $conteiner)
    {
        $this->request = $request;
        $this->conteiner = $conteiner;
    }

    /**
     * Отправка ответа клиенту
     * 
     * @return string
     */
    public function send($controller = DEFAULT_CONTROLLER, $action = '', array $get_params = [], array $action_params = [], $header = 'HTTP/1.1 200 Ok')
    {
        $this->setHeader($header);
        $this->setGetParams($get_params);
        $ctrl = $this->getController($controller);
        $act = $this->getAction($action);
        call_user_func_array([$ctrl, $act], $action_params);
        $ctrl->response();
    }
    
    /**
     * Определение контроллера
     * @param string $controller
     * @return object
     */
    public function getController (string $controller)
    {
        return $this->conteiner->get("controller.$controller", [$this->request]);
    }
    
    /**
     * Определение метода контроллера
     * @param string $action
     * @return string
     */
    public function getAction (string $action)
    {
        $action != '' ?: $action = 'index';
        return sprintf('%sAction', $action);  
    }
    
    /**
     * Установка Get параметров
     * @param array $params
     */
    public function setGetParams (array $params)
    {
        foreach ($params as $key => $value){
            $this->request->get[$key] = $value;
        }
    }
        
    /**
     * Установка заголовков ответа сервера
     * 
     * @return string
     */
    public function setHeader(string $header)
    {
        return header($header);
    }
    
        
}
