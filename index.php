<?php
/*
 * точка входа
 */    
    include_once 'config.php';
                     
    /* ЧПУ */
    $params = explode('/', isset($_GET['q']) ? $_GET['q']: '');
    $id = isset($params[1]) ? $params[1] : NULL;
    $params_cnt = count($params);
    
    if($params[$params_cnt - 1] == ''){
        unset($params[$params_cnt - 1]);
    }
    
    /* определяем метод, по умолчанию indexAction */
    $action = sprintf('%sAction', isset($params[0]) ? $params[0] : 'index');
    
    /* определяем контроллер, по умолчанию PostController */
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
                $action = 'er404Action';
		break;
    }
    
    $controller = sprintf('controller\%sController', $controller);
        
    $controller = new $controller();

    $controller->$action($id); 

    $controller->response();
        
   
   