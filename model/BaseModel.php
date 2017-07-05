<?php

/**
 * Базовая модель
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */

namespace model;

use core\DBDriverInterface,
    core\exception\ValidatorException,
    core\ValidatorInterface;

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

    /**
     * экземпляр класса validator
     * @var ValidatorInterface 
     */
    protected $validator;
    

    public function __construct(DBDriverInterface $db, ValidatorInterface $validator)
    {
        $this->db = $db;
        $this->validator = $validator;
    }

    /**
     * Вывод всех записей из таблицы $this->table
     * 
     * @return bool
     */
    public function getAll()
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
    public function getOne($id)
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
    public function getRandLimit($count)
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
        return $this->db->Delete("{$this->table}", "{$this->id_name} = :id", ['id' => $id]);
    }

    /**
     * добавление статьи 
     * @param array $params
     * 
     * @return bool; 
     */
    public function add(array $params)
    {
        $this->validator->run($params);
        var_dump($this->validator->clean);
        if (!empty($this->validator->errors)) {
            throw new ValidatorException(implode(' ', $this->validator->errors));
        } else {
            return $this->db->Insert("{$this->table}", $this->validator->clean);
        }
    }

    /**
     * Изменение статьи
     * @param array $params 
     * 
     * @return bool;
     */
    public function edit(array $params)
    {
        $this->validator->run($params);
        
       
        $id = $params[$this->id_name];
        unset($params[$this->id_name]);
        
        if (!empty($this->validator->errors)) {
            throw new ValidatorException(implode(' ', $this->validator->errors));
        } else {
            
            return $this->db->Update("$this->table", $this->validator->clean, "{$this->id_name} = :id", ['id' => $id]);
        }    
    }

}
