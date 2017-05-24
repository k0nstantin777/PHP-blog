<?php
    
/*
 * основная модель блога: авторизация, подключение шаблонов, шифрование, тестирование
 */
    
    include_once 'model/db.php';
    
    /* аутентификация */
    function isAuth(){
        if(!isset($_SESSION['auth'])){
            $login = getCookie('login');
            $pass = getCookie('password');
            if ($user = get_user($login)){
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
    
    /* регистрация */
    function registration ($login, $password){
        $sql = "INSERT INTO users (login, password) VALUES (:login, :password)";
        db_query($sql, ['login' => $login, 'password' => $password]);
        return true;
    }
    
    /* получить данные юзера из БД по логину */
    function get_user($login){
        $sql = "SELECT * FROM users WHERE login = :login";
        $query = db_query($sql, ['login' => $login]);
        return $query->fetch();
    }
    
    /* очистить куки */
    function deleteCookie($name){
        setcookie($name, '', time() - 1);
        unset($_COOKIE[$name]); 
    }
    
    /*получить куки */
    function getCookie($cookie){
        return isset($_COOKIE[$cookie]) ? $_COOKIE[$cookie] : null;
    }


    /*шифрование*/
    function myCrypt($str){
        return hash('sha256', $str . SAULT);
    }

    /* проверка на корректность ввода*/
    function check_name($name, $type){
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
    function template($path, $vars = []){
        extract($vars);
        ob_start();
        include("view/$path.php");
        return ob_get_clean();
    }
    
     /* вывод переменных, пользовательских и глобальных массивов для отладки*/
    function testing ($data, $globalarray = []){
        echo '<pre>'; 
        if ($data != ''){
            echo 'user_data:';
            print_r ($data);
            echo '<br>';
        }
        if (in_array('get', $globalarray)){
            echo '$_GET:';
            print_r($_GET);
        }
        if (in_array('post', $globalarray)){
            echo '$_POST:';
            print_r($_POST);
        } 
        if (in_array('session', $globalarray)){
             echo '$_SESSION:';
            print_r($_SESSION);
        }
        if (in_array('cookie', $globalarray)){
            echo '$_COOKIE:';
            print_r($_COOKIE);
        }
        if (in_array('server', $globalarray)){
            echo '$_SERVER:';
            print_r($_SERVER);
        }
        echo '</pre>';
    }
    
    