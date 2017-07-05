<?php

/**
 * Модель Юзеров
 *
 * @author bumer
 */

namespace model;

use model\BaseModel,
    core\DBDriverInterface,
    core\ValidatorInterface;

class UserModel extends BaseModel
{

    public function __construct(DBDriverInterface $db, ValidatorInterface $validator)
    {
        parent::__construct($db, $validator);
        $this->table = 'users';
        $this->id_name = 'login';
        $this->validator->setSchema([
			
			'id_user' => [
				'type' => 'integer',
				'require' => false
			],

			'login' => [
				'type' => 'string',
				'length' => 20,
				'require' => true,
                                'name' => 'Логин'
			],

			'password' => [
				'type' => 'string',
				'length' => 255,
				'require' => true, 
                                'name' => 'Пароль'
			],
                        
                        'dt' => [
				'type' => 'string',
				'length' => 20,
				'require' => false
  			]

		]);
    }
}
