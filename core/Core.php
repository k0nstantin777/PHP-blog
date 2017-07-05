<?php

/**
 * Класс статических вспомогательных функций
 *
 * @author bumer
 */
namespace core;
use model\UserModel,
    core\DB, 
    core\DBDriver,
    core\Validator;

class Core {
    /* аутентификация */
     public static function isAuth()
    {
        if(!isset($_SESSION['auth'])){
            $login = self::getCookie('login');
            $pass = self::getCookie('password');
            $userLogin = new UserModel(new DBDriver(DB::get()), new Validator());
            if ($user = $userLogin->getOne($login)){
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
     public static function myCrypt($str){
        return hash('sha256', $str . SAULT);
    }
    
    /* проверка на корректность ввода*/
     public static function checkName($name, $type){
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
    
    /*получить куки */
     public static function getCookie($cookie)
    {
        return isset($_COOKIE[$cookie]) ? $_COOKIE[$cookie] : null;
    }
    
    /* очистить куки */
     public static function deleteCookie($name)
    {
        setcookie($name, '', time() - 1);
        unset($_COOKIE[$name]); 
    }
    
}
