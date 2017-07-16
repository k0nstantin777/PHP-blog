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
        $this->id_name = 'id_user';
        $this->validator->setSchema([
			
			'id_user' => [
				'type' => 'integer',
				'require' => false
			],

			'login' => [
				'type' => 'string',
				'length' => [5, 20],
				'require' => true,
                                'name' => 'Логин'
			],

			'password' => [
				'type' => 'string',
				'length' => [4, 20],
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
    
    public function getByLogin($login)
    {
        $user = $this->db->Query("SELECT * FROM {$this->table} WHERE login = :login", ['login' => $login]);
        
        return !empty($user) ? $user[0] : false;                
    } 
    
    public function addUser($params)
    {
        return $this->db->Insert("{$this->table}", $params);                
    }
}
