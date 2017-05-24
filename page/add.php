<?php
/*
* добавление статьи 
*/
                 
    /*аутентификация*/
    if(!isAuth()){
        header("Location: ".BASE_PATH. "login");
        exit();
    } else {
        $login = true;
    }  
    
    /* проверка отправки формы методом POST */
    if(count($_POST) > 0){
        $name = trim(htmlspecialchars($_POST['title']));
        $text = trim(htmlspecialchars($_POST['content']));
        if($name == '' || $text == ''){
            $msg = 'Заполните все поля';
        } elseif (!check_name($name, 'article')){ 
            $msg = 'Запрещенные символы в поле "Имя"'; 
        } else {
            add_article($name, $text);     
            header("Location:".BASE_PATH. "posts?success=add");
            exit();
        } 
    } else {
        /* зашли на страницу методом GET */
        $name = '';
        $text = '';
        $msg = '';
                
    }
       
    $inner = template('view_add', [
        'name' => $name,
        'text' => $text,
        'back' => $_SERVER['HTTP_REFERER'],
        'msg'  => $msg
    ]);
    
    $title = 'Добавить статью';