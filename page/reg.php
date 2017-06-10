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
        } elseif (!$mUsers->checkName($login, 'user')){ 
            $msg = 'Запрещенные символы в поле "Логин"'; 
        } elseif ($mUsers->getOne($login)!==false){
            $msg = 'Логин занят!'; 
        } else {
            $mUsers->registration (['login'=> $login, 'password' =>$mUsers->myCrypt($password)]);     
            header("Location: ".BASE_PATH. "login?success=reg");
            exit();
        } 
    } else {
        /* зашли на страницу методом GET */
        
        $login = '';
        $password = '';
        $msg = '';
    }
      
    $inner = $mArticles->template('view_reg', [
        'back' => $_SERVER['HTTP_REFERER'],
        'msg'  => $msg,
        'login' => $login
    ]);
    $title = 'Регистрация';
   

        

        
