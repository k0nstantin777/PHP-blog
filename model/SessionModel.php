<?php


namespace model;

use model\BaseModel,
    core\DBDriverInterface,
    core\ValidatorInterface,
    core\ArrayHelper;

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
     * Присваивание параметров сессии
     * @param string $param
     * @param string $value
     * @return string $_SESSION[$param] = $value
     */
    public function setSessionParam ($param, $value)
    {
        return $_SESSION[$param] = $value;
    }
            
    /**
     * Поиск и удаление предыдущих сессий юзера $userId старше 30 минут
     * @param integer $userId
     * @return void
     */
    public function delOldSession ($userId)
    {
        $sessions = $this->db->Query("SELECT * FROM {$this->table} WHERE user_id = :user_id ", ['user_id' => $userId]);
                        
        if (!empty($sessions)){
                for ($i = 0; $i < count($sessions); $i++){
                    $last_active = time() - strtotime($sessions[$i]['updated_at']); 
                    if ($last_active > 30*60){
                       $this->delete($sessions[$i][$this->id_name]);
                    }
                }
        }
    }
    
    /**
     * Получение id сессии из таблицы session по текущему логину $login
     * @param string $login
     * @return integer id 
     */
    public function getIdSessionByLogin ($login)
    {
        $id_session = $this->db->Query("SELECT id FROM {$this->table}
                                        WHERE user_id
                                        IN 
                                        (SELECT id_user FROM users WHERE login = :login AND sid = :sid)",
                                        ['login' => $login, 'sid' => session_id()]);
        
        return !empty($id_session) ? $id_session[0][$this->id_name] : false;
    }
    
    public function getSessionBySid ($sid)
    {
        $session = $this->db->Query("SELECT * FROM {$this->table} WHERE sid = :sid", ['sid' => $sid]);
        
        return !empty($session) ? $session[0] : false;
        
    }
    
    
}
