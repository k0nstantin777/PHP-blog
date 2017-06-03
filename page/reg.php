<?php
/*
 *  регистрация
 */

    $msg = '';
    if(count($_POST) > 0){
        $login = trim(htmlspecialchars($_POST['login']));
        $password = trim(htmlspecialchars($_POST['password']));
        if($login == '' || $password == ''){
            $msg = 'Заполните все поля';
        } elseif (!check_name($login, 'user')){ 
            $msg = 'Запрещенные символы в поле "Логин"'; 
        } elseif (count(get_user($login))> 0){
            $msg = 'Логин занят!'; 
        } else {
            registration ($login, myCrypt($password));     
            header("Location: ".BASE_PATH. "login?success=reg");
            exit();
        } 
    } else {
        /* зашли на страницу методом GET */
        
        $login = '';
        $password = '';
        $msg = '';
    }
      
    $inner = template('view_reg', [
        'back' => $_SERVER['HTTP_REFERER'],
        'msg'  => $msg,
        'login' => $login
    ]);
    $title = 'Регистрация';
   

        

        
