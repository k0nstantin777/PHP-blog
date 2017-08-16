<?php

namespace core;

use core\exception\ServiceProviderException,
    core\exception\BaseException;


class ServiceContainer
{
    private $container = [];
    private $init_objects = [];
    
    public function register (string $name, \Closure $service)
    {
        // проверить существование имени в контейнере, бросить исключение если есть
      
            if (isset($this->container[$name])){
                throw new ServiceProviderException (sprintf("Function with name '%s' already registered in ServiceProvider", $name));
            }
            $this->container[$name] = $service;
      
    }

    public function get(string $name, array $params = [])
    {
        // проверка на существование;
            if (!isset($this->container[$name])){
                throw new ServiceProviderException (sprintf("Function with name '%s' not registered in ServiceProvider", $name));
            }
                      
            if (!isset($this->init_objects[$name])){
                $this->init_objects[$name] = call_user_func_array($this->container[$name], $params);
            } 
            
            return $this->init_objects[$name];     
           
    }
}
