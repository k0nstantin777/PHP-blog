<?php

namespace core\helper;

/**
 * Description of Cookie
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class Cookie
{
    
    public function get($name)
    {
        return $_COOKIE[$name] ?? null;
    }
    
    public function set ($name, $value, $expire)
    {
        setcookie($name, $value, strtotime("+$expire"), '/');
    }
    
    public function delete ($name)
    {
        setcookie($name, '', time() - 1);
        unset($_COOKIE[$name]); 
    }
}
