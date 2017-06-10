<?php
   /* Страница авторизации */
    
    $msg = '';
    //проверка на POST или GET
    if (count($_POST) > 0) {
              
        $userLogin = $mUsers->getOne($_POST['login']);
        if($_POST['login'] == '' || $_POST['password'] == ''){
            $msg = 'Заполните все поля'; 
        } elseif ($_POST['login'] != '' && $userLogin === false){
            $msg = 'Пользователя с таким логином не существует';
        } else {
            if($_POST['login'] == $userLogin['login'] && $mUsers->myCrypt($_POST['password']) == $userLogin['password']){
                $_SESSION['auth'] = true;
                $_SESSION['login'] = $userLogin['login'];

                if(isset($_POST['remember'])){
                    setcookie('login', $userLogin['login'], time() + 3600 * 24 * 365);
                    setcookie('password', $userLogin['password'], time() + 3600 * 24 * 365);
                }
                header("Location:". BASE_PATH . 'posts');
                exit();
            } else {
                $msg = 'Неправильный пароль!';
            }
        }     
    } else {
        unset($_SESSION['auth']);
        $mUsers->deleteCookie('login');
        $mUsers->deleteCookie('password');
        $login = false;
        if (isset($_GET['success']) && !empty($_GET['success'])){
            $success = $_GET['success'];
            $msgs = ['reg' => 'Регистрация прошла успешно, теперь вы можете авторизоваться'];

            $msg = ($msgs[$success]) ? $msgs[$success] : '';
        
        } 
    }
    
    
        
    $inner = $mUsers->template('view_login', [
        'msg'  => $msg
      
    ]);
    
    $title = 'Авторизация';