<?php

namespace core;

/**
 * Description of Session
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class Session
{
    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }
    
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    public function delete ($key)
    {
        unset($_SESSION[$key]); 
    }
}
