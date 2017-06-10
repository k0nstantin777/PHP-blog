<?php
/*
* добавление статьи 
*/
                 
    /*аутентификация*/
    if(!$mUsers->isAuth()){
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
        } elseif (!$mArticles->checkName($name, 'article')){ 
            $msg = 'Запрещенные символы в поле "Имя"'; 
        } else {
            $mArticles->addArticle(['title'=>$name, 'content'=> $text]);     
            header("Location:".BASE_PATH. "posts?success=add");
            exit();
        } 
    } else {
        /* зашли на страницу методом GET */
        $name = '';
        $text = '';
        $msg = '';
                
    }
       
    $inner = $mArticles->template('view_add', [
        'name' => $name,
        'text' => $text,
        'back' => $_SERVER['HTTP_REFERER'],
        'msg'  => $msg
    ]);
    
    $title = 'Добавить статью';