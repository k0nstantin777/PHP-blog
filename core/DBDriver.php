<?php

namespace core;
use core\DBDriverInterface;

/**
 * DBDriver
 *
 * @author bumer
 */
class DBDriver implements DBDriverInterface
{

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * Произвользная выборка из БД
     * @param string $sql
     * @param array $params параметры запроса
     * 
     * @return array 
     */
    public function Query($sql, array $params = [])
    {
        try {
            $q = $this->pdo->prepare($sql);
            $q->execute($params);
            $query = $q->fetchAll();
        } catch (\PDOException $e){
            die (nl2br(
                        'Filed connect to DB'.PHP_EOL
                       .'Message: '.$e->getMessage().PHP_EOL
                       .'Trace: '.PHP_EOL.$e->getTraceAsString()  
                    ));
        }
                    
        return $query;
    }

    /**
     * Добавление записи массива $obj в таблицу $table 
     * @param string $table
     * @param array $obj
     * 
     * @return integer last inserted id
     */
    public function Insert($table, array $obj)
    {
        $columns = [];
        $masks = [];

        foreach ($obj as $key => $value) {

            $columns[] = $key;
            $masks[] = ":$key";

            if ($value === null) {
                $obj[$key] = 'NULL';
            }
        }

        $columns_s = implode(',', $columns);
        $masks_s = implode(',', $masks);

        $query = "INSERT INTO $table ($columns_s) VALUES ($masks_s)";

        try {
            $q = $this->pdo->prepare($query);
            $q->execute($obj);
        } catch (\PDOException $e){
            die (nl2br(
                        'Filed connect to DB'.PHP_EOL
                       .'Message: '.$e->getMessage().PHP_EOL
                       .'Trace: '.PHP_EOL.$e->getTraceAsString()  
                    ));
        }

        return $this->pdo->lastInsertId();
    }

    /**
     * Обновление таблицы $table, записать массив $obj c условием $where
     * @param string $table
     * @param array $obj
     * @param string $where
     * @param array $params параметры запроса
     * 
     * @return integer updated rows count
     */
    public function Update($table, array $obj, $where, array $params = [])
    {
        $sets = [];

        foreach ($obj as $key => $value) {

            $sets[] = "$key = :$key";

            if ($value === NULL) {
                $obj[$key] = 'NULL';
            }
        }

        $sets_s = implode(', ', $sets);
        $query = "UPDATE $table SET $sets_s WHERE $where";
         
        try {
            $q = $this->pdo->prepare($query);
            $q->execute(array_merge($obj,$params));
        } catch (\PDOException $e){
            die (nl2br(
                        'Filed connect to DB'.PHP_EOL
                       .'Message: '.$e->getMessage().PHP_EOL
                       .'Trace: '.PHP_EOL.$e->getTraceAsString()  
                    ));
        }

        return $q->rowCount();
    }

    /**
     * Удаление записи с условием $where из таблицы $table
     * @param string $table
     * @param string $where
     * @param array $params параметры запроса
     * 
     * @return integer deleted rows count
     */    
    public function Delete($table, $where, array $params = [])
    {
        $query = "DELETE FROM $table WHERE $where";
        
        try {
            $q = $this->pdo->prepare($query);
            $q->execute($params);
        } catch (\PDOException $e){
            die (nl2br(
                        'Filed connect to DB'.PHP_EOL
                       .'Message: '.$e->getMessage().PHP_EOL
                       .'Trace: '.PHP_EOL. $e->getTraceAsString()  
                    ));
        }

        return $q->rowCount();
    }
        
}
