<?php
/*
 * точка входа
 */    
    
    session_start();
        
    include_once 'config.php';
    include_once 'DB.php';
    
    function __autoload($classname)
    {
	include_once __DIR__.'/model/'.$classname . '.php';
    }
    
    $mArticles = new PostModel(DB::get());
    $mUsers = new UserModel(DB::get());
        
    /*проверка логин*/
           
    if(!$mUsers->isAuth()){
        $login = false;
        $user = 'Гость';
    } else {
        $login = true;
        $user = $_SESSION['login'];
    }
    
    /* ЧПУ */
    $params = explode('/', $_GET['q']);
    $params_cnt = count($params);
    
    if($params[$params_cnt - 1] == ''){
        unset($params[$params_cnt - 1]);
    }
    
    $cname = isset($params[0]) ? $params[0] : 'index';
    $inc = "page/$cname.php";
    $articles = $mArticles->getAll();
    
    if(file_exists($inc) && $mArticles->checkName($cname, 'get')){
        include_once($inc);
    } else{
        $title = 'Ошибка 404';
        $inner = template('404');
    }
    
    /* подключение основного шаблона сайта */
    $html = $mArticles->template('view_main', [
        'title' => $title,
        'aside' => isset($aside) ? $aside:null,
        'content' => $inner,
        'user' => $user,
        'login' => $login,    
        'articles' => $articles
    ]);

    echo $html;
    
   
   