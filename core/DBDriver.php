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
     * 
     * @param string $sql
     * 
     * @return array 
     */
    public function Query($sql)
    {
        $q = $this->pdo->prepare($sql);
        $q->execute();

        if ($q->errorCode() != \PDO::ERR_NONE) {
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $q->fetchAll();
    }

    /**
     * Добавление записи массива $obj в таблицу $table 
     * 
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

        if ($q->errorCode() != \PDO::ERR_NONE) {
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $this->pdo->lastInsertId();
    }

    /**
     * Обновление таблицы $table, записать массив $obj c условием $where
     * 
     * @param string $table
     * @param array $obj
     * @param stirng $where
     * 
     * @return integer updated rows count
     */
    public function Update($table, array $obj, $where)
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
        $q->execute($obj);

        if ($q->errorCode() != \PDO::ERR_NONE) {
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $q->rowCount();
    }

    /**
     * Удаление записи с условием $where из таблицы $table
     * 
     * @param string $table
     * @param string $where
     * 
     * @return integer deleted rows count
     */    
    public function Delete($table, $where)
    {
        $query = "DELETE FROM $table WHERE $where";
        
        $q = $this->pdo->prepare($query);
        $q->execute();

        if ($q->errorCode() != \PDO::ERR_NONE) {
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $q->rowCount();
    }
        
}
