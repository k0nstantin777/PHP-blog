<?php

    session_start();
    
    function __autoload($classname)
    {
	include_once  __DIR__ .DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
    }
    
    /* константы */
    
    //корневая папка сайта
    define('BASE_PATH', DIRECTORY_SEPARATOR);
        
    //Драйвер БД
    define('DB_DRIVER', 'mysql');
    
    //хост БД
    define('DB_HOST', 'localhost');
    
    //Имя БД
    define('DB_NAME', 'id1764497_blog');
    
    //User БД
    define('DB_USER', 'id1764497_root');
    
    //Pass к БД
    define('DB_PASS', 'noskovkos');
    
    //соль для получения Хеша
    define('SAULT', '3428732jhgjdahf');
    
    //кодировка сайта
    define('DB_CHARSET', 'utf8');
    
    //контроллер по умолчанию
    define('DEFAULT_CONTROLLER', 'post' );
    
    //метод контроллера по умолчанию
    define('DEFAULT_ACTION', 'index');
    
    //режим разработчика: true - включен, false - выключен
    define('DEVELOP', false);
    
    //директорая хранения логов
    define('LOG_DIR', __DIR__ . '/logs');
    
    