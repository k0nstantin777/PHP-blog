<?php

    session_start();
    
    function __autoload($classname)
    {
	include_once  __DIR__ .DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
    }
    
    /* константы */
    define('BASE_PATH', '/');
    
    define('DB_DRIVER', 'mysql');
    
    define('DB_HOST', 'localhost');
    
    define('DB_NAME', 'id1764497_blog');
         
    define('DB_USER', 'id1764497_root');
    
    define('DB_PASS', 'noskovkos');
    
    define('SAULT', '3428732jhgjdahf');
    
    define('DB_CHARSET', 'utf8');