<?php

/**
 * Базовая модель
 *
 * @author bumer
 */
namespace model;

abstract class BaseModel 
{
    protected $pdo;
    protected $table;
    protected $id;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /* вывод всех записей из таблицы $this->table  */
    public function getAll () 
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY dt DESC"); 
        return $stmt->fetchAll();
    }
    
    /* вывод записи с $id  из таблицы $this->table*/
    public function getOne ($id) 
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->id} = :id");
        $stmt->execute(['id'=> $id]);
        return $stmt->fetch();
    }
    
     /* выбор случайных записей из таблицы {$this->table} в количестве $count */
    public function getRandLimit ($count)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} ORDER BY RAND() LIMIT :count "); 
        $stmt->bindParam(':count', $count, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }        
    
    /* удаление одной записи c $id из таблицы $this->table */
    public function delete($id) 
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->id} = :id");
        return $stmt->execute(['id'=> $id]);
    }
}
 
