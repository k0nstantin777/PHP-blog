<?php
/*
 * страница одной статьи
 */    

     if(!isAuth()){
        $login = false;
    } else {
        $login = true;
    }
    
    $flag = true;
    $article = get_article($params[1]);
    if ($article === false){
        $flag = false;
    } 
    

    if ($flag === true){
    $inner = template('view_post', [
        'login' => $login,
        'article' => $article,
        'back' => $_SERVER['HTTP_REFERER'],
    ]);
        $title = $article['title'];
    } else {
        $title = 'Ошибка 404';
        $inner = template('404');
    }
   