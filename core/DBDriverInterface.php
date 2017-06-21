<?php

namespace core;

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
     * @param string $fetch тип выводимых данных
     *
     * @return array|integer
     */
    public function Query($sql);

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
     * 
     * @return integer updated rows count
     */
    public function Update($table, array $obj, $where);

    /**
     * @param string $table
     * @param string $where
     * 
     * @return integer deleted rows count
     */
    public function Delete($table, $where);
}
