<?php

/**
 * Модель постов
 *
 * @author bumer
 */
namespace model;
use model\BaseModel,
    core\DBDriverInterface;


class PostModel extends BaseModel
{
    public function __construct(DBDriverInterface $db)
    {
        parent::__construct($db);
        $this->table = 'articles';
        $this->id_name = 'id_article';
    }
}
