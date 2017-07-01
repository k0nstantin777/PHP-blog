<?php

/**
 * Модель Юзеров
 *
 * @author bumer
 */

namespace model;

use model\BaseModel,
    core\DBDriverInterface;

class UserModel extends BaseModel
{

    public function __construct(DBDriverInterface $db)
    {
        $this->table = 'users';
        $this->id_name = 'login';
        parent::__construct($db);
    }
}
