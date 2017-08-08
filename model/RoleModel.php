<?php


namespace model;

use core\database\DBDriverInterface,
    core\database\DBDriver,
    core\module\ValidatorInterface;


/**
 * Description of RoleModel
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class RoleModel extends BaseModel
{
    public function __construct(DBDriverInterface $db, ValidatorInterface $validator)
    {
        parent::__construct($db, $validator);
        $this->table = 'roles';
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
}
