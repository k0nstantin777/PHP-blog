<?php
 
    session_start();

    
    function __autoload($classname)
    {
	include_once  __DIR__ .DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
    }
    
    /* константы */
    
    //корневая папка сайта
    define('BASE_PATH', DIRECTORY_SEPARATOR);
    
    define('DB_DRIVER', 'mysql');
    
    define('DB_HOST', 'localhost');
    
    define('DB_NAME', 'id1764497_blog');
    
    define('DB_USER', 'id1764497_root');
    
    define('DB_PASS', 'noskovkos');
    
    //соль для получения Хеша
    define('SAULT', '3428732jhgjdahf');
    
    define('DB_CHARSET', 'utf8');
    
    define('DEFAULT_CONTROLLER', 'post' );
    
    //режим разработчика: true - включен, false - выключен
    define('DEVELOP', true);
    
    define('LOG_DIR', __DIR__ . '/logs');
    
    