<?php

/**
 * Базовая модель
 *
 * @author bumer
 */
namespace model;

use core\DBDriverInterface;

abstract class BaseModel 
{
    /**
     * @var DBDriverInterface 
     */
    protected $db;
    /**
     * Имя таблицы БД
     * @var string 
     */
    protected $table;
    /**
     * Имя столбца уникального идентификатора в БД;
     * @var string
     */
    protected $id_name;

    public function __construct(DBDriverInterface $db)
    {
        $this->db = $db;
    }
    
    /**
     * Вывод всех записей из таблицы $this->table
     * 
     * @return bool
     */
    public function getAll () 
    {
        return $this->db->Query("SELECT * FROM {$this->table} ORDER BY dt DESC"); 
    }
    
    /**
     * Выбор записи с $id  из таблицы $this->table
     * 
     * @param integer $id
     * 
     * @return array|false;
     */
    public function getOne ($id) 
    {
        $one = $this->db->Query("SELECT * FROM {$this->table} WHERE {$this->id_name} = :id LIMIT 1", ['id' => $id]);
        return !empty($one) ? $one[0] : false;
    }
    
    /**
     * Выбор случайных записей из таблицы {$this->table} в количестве $count
     * 
     * @param integer $count
     * 
     * @return bool;
     */
    public function getRandLimit ($count)
    {
        return $this->db->Query("SELECT * FROM {$this->table} ORDER BY RAND() LIMIT $count"); 
    }        
     
    /**
     * Удаление одной записи c $id из таблицы $this->table
     * 
     * @param integer $id
     * 
     * @return bool;
     */
    public function delete($id) 
    {
        return $this->db->Delete("{$this->table}", "{$this->id_name} = :id", ['id'=>$id]);
    }
    
    /**
     * добавление статьи 
     * 
     * @param array $params
     * 
     * @return bool; 
     */
    public function add(array $params)
    {
        return $this->db->Insert("{$this->table}", $params);
    }
    
    /**
     * Изменение статьи
     * 
     * @param array $params 
     * 
     * @return bool;
     */
    public function edit(array $params)
    {
        $id = $params[$this->id_name];
        unset ($params[$this->id_name]);
        
        return $this->db->Update("$this->table", $params, "{$this->id_name} = :id", ['id'=>$id]);
    }
}
 
