<?php

namespace core;

use core\exception\ServiceConteinerException;


class ServiceContainer
{
    private $container = [];
    private $init_objects = [];
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function register (string $name, \Closure $service)
    {
        // проверить существование имени в контейнере, бросить исключение если есть
        try {    
                if (isset($this->container[$name])){
                    throw new ServiceConteinerException (sprintf("Function with name '%s' already registered in ServiceConteiner", $name));
                }
                $this->container[$name] = $service; 
            } catch (ServiceConteinerException $e) {
           
                self::get('errorHandler.screen', [$this->request])->handle($e, 'Ooooops... Something went wrong!');

            }      
      
    }

    public function get(string $name, array $params = [])
    {
        // проверка на существование;
        try {    
        
                if (!isset($this->container[$name])){
                    throw new ServiceConteinerException (sprintf("Function with name '%s' not registered in ServiceConteiner", $name));
                }

                if (!isset($this->init_objects[$name])){
                    $this->init_objects[$name] = call_user_func_array($this->container[$name], $params);
                } 
                
                return $this->init_objects[$name];     
            
            } catch (ServiceConteinerException $e) {
           
                self::get('errorHandler.screen', [$this->request])->handle($e, 'Ooooops... Something went wrong!');

            }    
        
        
           
    }
}
