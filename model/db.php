<?php

/*
 * модель работы с БД
 */

/*подключение к БД*/
    function dbConnect (){
        static $db;
        if ($db == null){
            $db = new PDO(DB, DB_USER, DB_PASS);
            $db->exec("SET NAMES UTF8");
        }
        return $db;
    }

/* проверка и вывод ошибок SQL запросов */
    function db_error_report($query){
        $err = $query->errorInfo();

        if($err[0] != PDO::ERR_NONE){
           exit($err[2]);
        }
    }
/* обработка SQL запросов */    
    function db_query ($sql, $params = []){
        $db = dbConnect();
        $query = $db->prepare($sql);
        $query->execute($params);
        db_error_report($query);
        return $query;
    }