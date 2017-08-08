<?php

namespace core\database;
use core\Core;

/**
 * DBDriver
 *
 * @author bumer
 */
class DBDriver implements DBDriverInterface
{

    const FETCH_ONE = 0;
    const FETCH_ALL = 1;
    
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
    public function Query($sql, array $params = [], $fetch = self::FETCH_ALL)
    {
        $q = $this->pdo->prepare($sql);
        $q->execute($params);
        
        return $fetch === self::FETCH_ALL ? $q->fetchAll() : $q->fetch();
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

        $q = $this->pdo->prepare($query);
        $q->execute($obj);
        
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
       
            $q = $this->pdo->prepare($query);
            $q->execute(array_merge($obj,$params));
       
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
        
        $q = $this->pdo->prepare($query);
        $q->execute($params);
       
        return $q->rowCount();
    }
        
}
