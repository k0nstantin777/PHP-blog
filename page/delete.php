<?php
/*
 * удаление статьи
 */
        
    /*проверка логин*/
    if(!$mUsers->isAuth()){
        header("Location: ".BASE_PATH. "login");
        exit();
    }
    
    //удалеяем статью
    if ($mArticles->delete($params[1])){
        header("Location: ".BASE_PATH. "posts?success=delete");
        exit();
    } else {
        $title = 'Ошибка 404';
        $inner = $mArticles->template('404');
    }
    
    