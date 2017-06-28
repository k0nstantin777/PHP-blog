<?php

/**
 * Подключение к БД
 *
 * @author bumer
 */

namespace core;

use \PDO;

class DB
{

    private static $instance;

    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = self::connect();
        }

        return self::$instance;
    }

    private function connect()
    {

        $dsn = DB_DRIVER . ':' . 'host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $opt = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        
        try{
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opt);
        } catch (\PDOException $e){
            die (nl2br(
                        'Filed connect to DB'.PHP_EOL
                       .'Message: '.$e->getMessage().PHP_EOL
                       .'Trace: '.$e->getTraceAsString()  
                    ));
        }
        
        return $pdo;
    }

}