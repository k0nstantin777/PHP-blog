<?php
/*
 * точка входа
 */    
    
    session_start();
        
    include_once 'config.php';
    include_once 'DB.php';
    include_once 'Core.php';
    
    function __autoload($classname)
    {
	include_once __DIR__.'/model/'.$classname . '.php';
    }
                  
    /* ЧПУ */
    $params = explode('/', isset($_GET['q']) ? $_GET['q']: '');

    $params_cnt = count($params);
    
    if($params[$params_cnt - 1] == ''){
        unset($params[$params_cnt - 1]);
    }
           
    $action = sprintf('%sAction', isset($params[0]) ? $params[0] : 'index');
    
    $controller = isset($params[0]) ? $params[0] : 'post';
    switch ($controller) {
	case 'post':
        case 'posts':
        case 'edit':
        case 'add':
        case 'delete':   
		$controller = 'Post';
		break;
        case 'login':
        case 'reg':
		$controller = 'User';
		break;
            
        case 'contacts':
                $controller = 'Page';
                break;
	default:
		$controller = 'Base';
                $action = 'errorAction';
		break;
    }
    
    $controller .= 'Controller';
    $fileName = sprintf('controller/%s.php', $controller);
        
    if (!file_exists($fileName)) {
	echo '404 Error!';
	header("HTTP/1.1 404 Not Found");
	die;
    }   
    
    include_once $fileName;

    $controller = new $controller();

    $controller->$action(); 

    $controller->response();
        
   
   