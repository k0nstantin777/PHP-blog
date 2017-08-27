<?php


namespace model;

use core\database\DBDriverInterface,
    core\database\DBDriver,
    core\module\ValidatorInterface;

/**
 * Description of PrivsModel
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class PrivModel extends BaseModel
{
    public function __construct(DBDriverInterface $db, ValidatorInterface $validator)
    {
        parent::__construct($db, $validator);
        $this->table = 'privs';
        $this->id_name = 'id';
        $this->validator->setSchema([
			
			'id' => [
				'type' => 'integer',
				'require' => false
			],

			'name' => [
				'type' => 'string',
				'length' => 255,
				'require' => true,
                                'name' => 'Логин'
			],

			'discription' => [
				'type' => 'string',
				'length' => 'big',
				'require' => true, 
                                'name' => 'Пароль'
			],
               
                ]);
        
    }
    
    /**
     * Проверка наличия у пользователя $login привилегии $prive_name
     * @param string $login
     * @param string $prive_name
     * @return array
     */
    public function getByLoginAndPriveName ($login, $prive_name)
    {
        return  $this->db->Query("SELECT login, privs.name as priv_name
                                FROM {$this->table}
                                JOIN privs_to_roles ON privs.id = privs_to_roles.id_priv
                                JOIN users ON privs_to_roles.id_role = users.id_role
                                WHERE privs.name = :priv_name AND login = :login",
                                ['priv_name' => $prive_name, 'login' => $login],
                                DBDriver::FETCH_ONE);
    
    } 
    
    /**
     * Получение всех привилегий текущего пользователя (для отображения возможных действий в шаблонах view_xxx)
     * @param string $login
     * @return array
     */
    public function getAllByLogin ($login)
    {
        $prives = [];
        
        $results = $this->db->Query("SELECT privs.name as priv_name
                                 FROM {$this->table}
                                 JOIN privs_to_roles ON privs.id = privs_to_roles.id_priv
                                 JOIN users ON privs_to_roles.id_role = users.id_role
                                 WHERE login = :login",
                                 ['login' => $login],
                                 DBDriver::FETCH_ALL);
        
        for ($i = 0; $i < count($results); $i++){
            $prives [] = $results[$i]['priv_name'];
        }
        
        return $prives;
    }
    
    
}
