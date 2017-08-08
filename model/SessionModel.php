<?php


namespace model;

use model\BaseModel,
    core\database\DBDriverInterface,
    core\database\DBDriver,
    core\module\ValidatorInterface,
    core\helper\ArrayHelper;

/**
 * SessionModel
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class SessionModel extends BaseModel
{
    public function __construct(DBDriverInterface $db, ValidatorInterface $validator)
    {
        parent::__construct($db, $validator);
        $this->table = 'session';
        $this->id_name = 'id';
        $this->validator->setSchema([
			
			'id' => [
				'type' => 'integer',
				'require' => false
			],

			'sid' => [
				'type' => 'integer',
				'require' => true,
                        ],
                        
                        'created_at' => [
				'type' => 'integer',
				'require' => false,
                        ],
        
                        'updated_at' => [
				'type' => 'integer',
				'require' => false,
                        ],
                            
			'user_id' => [
				'type' => 'integer',
				'require' => true,
                        ],

		]);
    }
    
    /**
     * Поиск и удаление предыдущих сессий юзера $userId старше 30 минут
     * @param integer $userId
     * @return void
     */
    public function delOld ($userId)
    {
        return $this->db->Delete("{$this->table}", "user_id = :user_id AND ((NOW() - updated_at) > 30*60)", ['user_id' => $userId]);
    }
           
    /**
     * Получение записи из таблицы session по ключу $sid
     * @param string $sid
     * @return array
     */
    public function getBySid ($sid)
    {
        return $session = $this->db->Query("SELECT * FROM {$this->table} WHERE sid = :sid", ['sid' => $sid], DBDriver::FETCH_ONE);
    }
    
    
}
