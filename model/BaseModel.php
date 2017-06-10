<?php

/**
 * Базовая модель
 *
 * @author bumer
 */
abstract class BaseModel 
{
    protected $pdo;
    protected $table;
    protected $id;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /* вывод всех записей из БД */
    public function getAll () 
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY dt DESC"); // $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }
    
    /* вывод одной записи из БД */
    public function getOne ($id) 
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->id} = :id");
        $stmt->execute(['id'=> $id]);
        return $stmt->fetch();
    }
    
    /* удаление одной записи из БД */
    public function delete($id) 
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->id} = :id");
        return $stmt->execute(['id'=> $id]);
    }
    
    /*получить куки */
    public function getCookie($cookie)
    {
        return isset($_COOKIE[$cookie]) ? $_COOKIE[$cookie] : null;
    }
    
    /* очистить куки */
    public function deleteCookie($name)
    {
        setcookie($name, '', time() - 1);
        unset($_COOKIE[$name]); 
    }
    
    /* аутентификация */
    public function isAuth()
    {
        if(!isset($_SESSION['auth'])){
            $login = $this->getCookie('login');
            $pass = $this->getCookie('password');
            if ($user = $this->getOne($login)){
                if($login == $user['login'] && $pass == $user['password']){
                    $_SESSION['auth'] = true;
                    $_SESSION['login'] = $user['login'];
                    return true;
                }
                return false;
            } 
            return false;
        }
        return true;
    }
    
    /*шифрование*/
    public function myCrypt($str){
        return hash('sha256', $str . SAULT);
    }
    
    /* проверка на корректность ввода*/
    public function checkName($name, $type){
        /* для проверки названия статьи: латиница, русские буквы, цифры, пробелы и дефис */
        if ($type == 'article'){
            return preg_match('/^([а-яА-ЯЁёa-zA-Z0-9- ]+)$/u', $name) > 0;
        } 
        /* для проверки логина: латиница, цифры, пробелы, дефис, земля, точка */
        elseif ($type == 'user'){
            return preg_match('/^([a-zA-Z0-9-_.]+)$/', $name) > 0;
        }     
        /* для проверки данных GET в адресной строке: латиница, цифры, пробелы и дефис */
        elseif ($type == 'get'){
            return preg_match('/^([a-zA-Z0-9-]+)$/', $name) > 0;
        }
    }
    
    /* подключение шаблона страницы*/ 
    public function template($path, $vars = []){
        extract($vars);
        ob_start();
        include("view/$path.php");
        return ob_get_clean();
    }
    
}
 