<?php

namespace core;

use core\exception\ServiceProviderException,
    core\exception\BaseException;

class ServiceContainer
{
    private $container = [];

    public function register (string $name, \Closure $service)
    {
        // проверить существование имени в контейнере, бросить исключение если есть
        try {
            if (isset($this->container[$name])){
                throw new ServiceProviderException (sprintf("Function with name '%s' already registered in ServiceProvider", $name));
            }
            $this->container[$name] = $service;
            
        } catch (ServiceProviderException $e){
            die($e->getMessage());
        }   
    }

    public function get(string $name, array $params = [])
    {
        // проверка на существование;
        try {
            if (!isset($this->container[$name])){
                throw new ServiceProviderException (sprintf("Function with name '%s' not registered in ServiceProvider", $name));
            }
            return call_user_func_array($this->container[$name], $params);
            
        } catch (ServiceProviderException $e){
            die($e->getMessage());
        }    
            
    }
}