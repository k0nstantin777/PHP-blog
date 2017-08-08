<?php

namespace core\database;

/**
 * Интерфейс драйвера работы с БД
 *
 * @author bumer
 */
interface DBDriverInterface
{

    public function __construct(\PDO $pdo);

    /**
     * @param string $sql запрос
     * @param array $params параметры запроса
     *
     * @return array|integer
     */
    public function Query($sql, array $params);

    /**
     * @param string $table таблица для вставки
     * @param array $obj массив полей для вставки в таблицу
     * 
     * @return integer last inserted id
     */
    public function Insert($table, array $obj);

    /**
     * @param string $table
     * @param array  $obj
     * @param string $where
     * @param array $params параметры запроса
     * 
     * @return integer updated rows count
     */
    public function Update($table, array $obj, $where, array $params);

    /**
     * @param string $table
     * @param string $where
     * @param array $params параметры запроса
     * 
     * @return integer deleted rows count
     */
    public function Delete($table, $where, array $params);
}
