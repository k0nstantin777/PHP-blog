<?php
/*
 * точка входа
 */    
    include_once 'config.php';
    
    $app = new core\Application();

    $app->run();
//    $request = new core\Request($_GET, $_POST, $_FILES, $_COOKIE, $_SERVER, $_SESSION);
//        
//    
//    /* определяем контроллер, по умолчанию PostController */
//    $controller = $request->getController();
//    
//    /* определяем метод, по умолчанию indexAction, если метод не найден то er404Action */
//    $action = $request->getAction();
//    
//    $controller = new $controller($request);
//
//    $controller->$action(); 
//
//    $controller->response();
        
   
   