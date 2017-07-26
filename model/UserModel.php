<?php

/**
 * Модель Юзеров
 *
 * @author bumer
 */

namespace model;

use model\BaseModel,
    core\DBDriverInterface,
    core\ValidatorInterface,
    core\ArrayHelper,
    core\exception\ValidatorException,
    core\DBDriver;

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
     * Проверка наличия у пользователя $login привилегии $prive_name
     * @param string $login
     * @param string $prive_name
     * @return array
     */
    public function getPrive ($login, $prive_name)
    {
        return  $this->db->Query("SELECT login, privs.name as priv_name
                                 FROM {$this->table}
                                 JOIN roles ON users.id_role = roles.id
                                 JOIN privs_to_roles ON roles.id = privs_to_roles.id_role
                                 JOIN privs ON privs_to_roles.id_priv = privs.id
                                 WHERE privs.name = :priv_name AND login = :login",
                                 ['priv_name' => $prive_name, 'login' => $login],
                                 DBDriver::FETCH_ONE);
    
    }                             
    
    /**
     * Получение всех привилегий текущего пользователя (для отображения возможных действий в шаблонах view_xxx)
     * @param string $login
     * @return array
     */
    public function getPrives ($login)
    {
        $prives = [];
        
        $results = $this->db->Query("SELECT privs.name as priv_name
                                 FROM {$this->table}
                                 JOIN roles ON users.id_role = roles.id
                                 JOIN privs_to_roles ON roles.id = privs_to_roles.id_role
                                 JOIN privs ON privs_to_roles.id_priv = privs.id
                                 WHERE login = :login",
                                 ['login' => $login],
                                 DBDriver::FETCH_ALL);
        
        for ($i = 0; $i < count($results); $i++){
            $prives [] = $results[$i]['priv_name'];
        }
        
        return $prives;
    }
    
}
