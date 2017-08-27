<?php

namespace model;

use core\database\DBDriverInterface,
    core\database\DBDriver,
    core\module\ValidatorInterface;

/**
 * Description of Privs_to_RolesModel
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class Privs_to_RolesModel extends BaseModel
{
    public function __construct(DBDriverInterface $db, ValidatorInterface $validator)
    {
        parent::__construct($db, $validator);
        $this->table = 'privs_to_roles';
        $this->id_name = '';
        $this->validator->setSchema([
			
			'id_priv' => [
				'type' => 'integer',
				'require' => true
			],

			'id_roles' => [
				'type' => 'integer',
				'require' => true
			]
         
                ]);
        
    }
    
    /**
     * Получение массива где ключами являются столбцы id_priv, а значением массив из всех id_role по id_prive
     * @return array
     */
    public function getAllPrivsAsKeyToRoleArr()
    {
        $rows = $this->db->Query("SELECT * FROM {$this->table}");

        $roles = [];
        foreach ($rows as $row){
           
            $roles [$row['id_priv']][] = $row['id_role'];
            
        }
        
        return $roles;                         
    }
          
    public function setPrivesToRoles ($rules)
    {
        return $this->db->Insert("{$this->table}", $rules);
    }
             
    public function delAll ()
    {
        return $this->db->Delete("{$this->table}", 1);
        
    }
}
