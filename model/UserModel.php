<?php

/**
 * Модель Юзеров
 *
 * @author bumer
 */
class UserModel extends BaseModel {
   
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->table = 'users';
        $this->id = 'login';
    }
    
    /* регистрация */
    public function add (array $params){
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (login, password) VALUES (:login, :password)");
        return $stmt->execute($params);
    }
  
}
