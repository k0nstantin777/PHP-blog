<?php
   /* Страница авторизации */
    
    $msg = '';
    if(count($_POST) > 0) {
        $user = get_user($_POST['login']);
        if ($user === false){
            $msg = 'Пользователя с таким логином не существует';
        } else {
            if($_POST['login'] == $user['login'] && myCrypt($_POST['password']) == $user['password']){
                $_SESSION['auth'] = true;
                $_SESSION['login'] = $user['login'];

                if(isset($_POST['remember'])){
                    setcookie('login', $user['login'], time() + 3600 * 24 * 365);
                    setcookie('password', $user['password'], time() + 3600 * 24 * 365);
                }
                header("Location:". BASE_PATH . 'posts');
                exit();
            }
            else{
                $msg = 'Неправильный пароль!';
            }
        }    
    }
    else{
        unset($_SESSION['auth']);
        deleteCookie('login');
        deleteCookie('password');
        $login = false;
        $user = 'Гость';
        if (isset($_GET['success']) && !empty($_GET['success'])){
            $success = $_GET['success'];
            $msgs = ['reg' => 'Регистрация прошла успешно, теперь вы можете авторизоваться'];

            $msg = ($msgs[$success]) ? $msgs[$success] : '';
        
        } 
    }
        
    $inner = template('view_login', [
        'msg'  => $msg,
        'login' => $login,
        'user' => $user
    ]);
    
    $title = 'Авторизация';