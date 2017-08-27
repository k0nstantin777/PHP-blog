<?php

/**
 * Модель Юзеров
 *
 * @author bumer
 */

namespace model;

use model\BaseModel,
    core\database\DBDriverInterface,
    core\module\ValidatorInterface,
    core\helper\ArrayHelper,
    core\exception\ValidatorException,
    core\database\DBDriver;

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
				'length' => 72,
				'require' => true, 
                                'name' => 'Пароль'
			],
        
                        'uncryptPass' => [
                                'type' => 'string',
                                'length' => [6, 20],
                                'check' => true, //если есть параметр check - поле не записывается в БД
                                'require' => true,
                                'name' => 'Пароль'
                        ],
                                                       
                        'dt' => [
				'type' => 'string',
				'length' => 20,
				'require' => false
  			],
                        
                        'id_role' => [
				'type' => 'integer',
				'require' => false
  			]
                        
        
                ]);
        
    }
    
    /**
     * Валидация полей формы без записи в БД
     * @param type $fields
     * @throws ValidatorException
     */
    public function getValidFields ($fields)
    {
        $this->validator->run($fields);
            if (!empty($this->validator->errors)) {
                throw new ValidatorException($this->validator->errors);
            } 
    }

    /**
     * Выборка из таблицы users по $login
     * @param string $login
     * @return array
     */
    public function getByLogin($login)
    {
        return $this->db->Query("SELECT * FROM {$this->table} WHERE login = :login", ['login' => $login], DBDriver::FETCH_ONE);
    } 
    
    /**
     * Получение роли юзера по $login
     * @param string $login
     * @return array
     */
    public function getRoleByLogin($login)
    {
        return $this->db->Query("SELECT r.* FROM {$this->table} AS u JOIN roles AS r ON u.id_role = r.id WHERE login = :login", ['login' => $login], DBDriver::FETCH_ONE);
    } 
              
}
