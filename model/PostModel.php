<?php

/**
 * Модель постов
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
namespace model;
use model\BaseModel,
    core\DBDriverInterface;


class PostModel extends BaseModel
{
    public function __construct(DBDriverInterface $db)
    {
        $this->table = 'articles';
        $this->id_name = 'id_article';
        parent::__construct($db);
    }
}
