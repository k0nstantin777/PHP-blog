<?php
/*
 * страница одной статьи
 */    

    if(!$mUsers->isAuth()){
        $login = false;
    } else {
        $login = true;
    }
    
    $flag = true;
       
    $article = $mArticles->getOne($params[1]);
    if ($article === false){
        $flag = false;
    } 
    
    if ($flag === true){
        $inner = $mArticles->template('view_post', [
            'login' => $login,
            'article' => $article,
            'back' => $_SERVER['HTTP_REFERER'],
        ]);
        $title = $article['title'];
    } else {
        $title = 'Ошибка 404';
        $inner = $mArticles->template('404');
    }
   