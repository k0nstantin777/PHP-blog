<?php
/*
 * точка входа
 */    
    
    session_start();
    
    include_once 'model/system.php';
    include_once 'model/articles.php';
    include_once 'config.php';
    
    /*проверка логин*/
    if(!isAuth()){
        $login = false;
        $user = 'Гость';
    } else {
        $login = true;
        $user = $_SESSION['login'];
    }
    
    
    $params = explode('/', $_GET['q']);
    $params_cnt = count($params);
    
    if($params[$params_cnt - 1] == ''){
        unset($params[$params_cnt - 1]);
    }
    
    $cname = isset($params[0]) ? $params[0] : 'index';
    $inc = "page/$cname.php";
   
    if(file_exists($inc) && check_name($cname, 'get')){
        include_once($inc);
    }
    else{
        $title = 'Ошибка 404';
        $inner = template('404');
    }
    
    $articles = get_all_articles();
    
    
    /* подключение основного шаблона сайта */
    $html = template('view_main', [
        'title' => $title,
        'aside' => isset($aside) ? $aside:null,
        'content' => $inner,
        'user' => $user,
        'login' => $login,    
        'articles' => $articles
    ]);

    echo $html;
    
   
   