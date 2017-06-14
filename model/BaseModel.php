<?php

/**
 * Базовая модель
 *
 * @author bumer
 */
abstract class BaseModel 
{
    protected $pdo;
    protected $table;
    protected $id;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /* вывод всех записей из БД */
    public function getAll () 
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY dt DESC"); 
        return $stmt->fetchAll();
    }
    
    /* вывод одной записи из БД */
    public function getOne ($id) 
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->id} = :id");
        $stmt->execute(['id'=> $id]);
        return $stmt->fetch();
    }
    
    /* удаление одной записи из БД */
    public function delete($id) 
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->id} = :id");
        return $stmt->execute(['id'=> $id]);
    }
      
}
 