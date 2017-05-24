<?php
/*
 * удаление статьи
 */
        
    /*проверка логин*/
    if(!isAuth()){
        header("Location: ".BASE_PATH. "login");
        exit();
    }
    
    //удалеяем статью
    if (get_article($params[1])){
        delete_article($params[1]);
        header("Location: ".BASE_PATH. "posts?success=delete");
        exit();
    } else {
        $title = 'Ошибка 404';
        $inner = template('404');
    }
    
    