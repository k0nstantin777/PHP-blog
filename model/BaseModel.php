<?php

/**
 * Базовая модель
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */

namespace model;

use core\database\DBDriverInterface,
    core\exception\ValidatorException,
    core\database\DBDriver,
    core\module\ValidatorInterface;

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
    public $id_name;

    /**
     * экземпляр класса validator
     * @var ValidatorInterface 
     */
    public $validator;
           
    public function __construct(DBDriverInterface $db, ValidatorInterface $validator)
    {
        $this->db = $db;
        $this->validator = $validator;
    }

    /**
     * Вывод всех записей из таблицы $this->table
     * 
     * @return array
     */
    public function getAll($sort = '')
    {
        $order='';
        if ($sort == 'new'){
            $order = 'ORDER BY dt DESC';
        } elseif ($sort == 'id'){
            $order = "ORDER BY {$this->id_name} ASC";
        }
        return $this->db->Query("SELECT * FROM {$this->table} $order");
    }

    /**
     * Выбор записи с $id  из таблицы $this->table
     * 
     * @param integer $id
     * 
     * @return array;
     */
    public function getOne($id)
    {
        return $one = $this->db->Query("SELECT * FROM {$this->table} WHERE {$this->id_name} = :id LIMIT 1", ['id' => $id], DBDriver::FETCH_ONE);
    }

    /**
     * Выбор случайных записей из таблицы {$this->table} в количестве $count
     * 
     * @param integer $count
     * 
     * @return array;
     */
    public function getRandLimit($count)
    {
        return $this->db->Query("SELECT * FROM {$this->table} ORDER BY RAND() LIMIT $count");
    }

    /**
     * Удаление одной записи c $id из таблицы $this->table
     * 
     * @param integer $id
     * 
     * @return void;
     */
    public function delete($id)
    {
        return $this->db->Delete("{$this->table}", "{$this->id_name} = :id", ['id' => $id]);
    }

    /**
     * добавление статьи 
     * @param array $params
     * 
     * @return void; 
     */
    public function add(array $params)
    {
        $this->validator->run($params);
        
        if (!empty($this->validator->errors)) {
            throw new ValidatorException($this->validator->errors);
        } 
       
        return $this->db->Insert("{$this->table}", $this->validator->clean);
        
    }

    /**
     * Изменение статьи
     * @param array $params 
     * 
     * @return void;
     */
    public function edit(array $params)
    {
        $this->validator->run($params);
       
        $id = $params[$this->id_name];
        
        if (!empty($this->validator->errors)) {
            throw new ValidatorException($this->validator->errors);
        } 
        
        return $this->db->Update("$this->table", $this->validator->clean, "{$this->id_name} = :id", ['id' => $id]);
    }
    
}
