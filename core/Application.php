<?php

namespace core;

use core\exception\PageNotFoundException,
    core\exception\AccessException,
    core\exception\BaseException,
    core\ServiceContainer,
    core\providers\ModelProvider,
    core\providers\UserProvider,
    core\providers\ErrorHandlerProvider,
    core\providers\ControllerProvider,
    core\helper\Session,
    core\helper\Cookie;

/**
 * Application движок сайта
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class Application
{

    /**
     * Объект класса core\Request
     * @var object
     */
    public $request;

    /**
     * Класс контроллера
     * @var stirng 
     */
    private $controller;

    /**
     * Вызываемый метод контроллера
     * @var string
     */
    private $action;
    
    private $container;

    public function __construct()
    {
        $this->initRequest();
        $this->handlingUri();
        $this->container = new ServiceContainer($this->request);
        (new ControllerProvider())->register($this->container);
        (new UserProvider())->register($this->container);
        (new ModelProvider())->register($this->container); 
        (new ErrorHandlerProvider())->register($this->container);
    }

    /**
     * Запуск блога
     * 
     */
    public function run()
    {
        try {
                         
            if (!$this->controller) {
                throw new PageNotFoundException();
            }
            
            $ctrl = new $this->controller($this->request, $this->container);
                      
            $action = $this->action;
            
            $ctrl->$action();

            $ctrl->response();
        } catch (\PDOException $e) {

            $this->container->get('errorHandler.logger')->handle($ctrl, $e, 'Ooooops... Something went wrong!');
            
        } catch (PageNotFoundException $e) {

            $this->container->get('errorHandler.screen')->handle($ctrl, $e, 'Error 404 - Page not found!');
             
        } catch (AccessException $e) {
           
            $this->container->get('errorHandler.screen')->handle($ctrl, $e, 'Access Denied!');
          
        } catch (\Exception $e) {
            
            $this->container->get('errorHandler.logger')->handle($ctrl, $e, 'Ooooops... Something went wrong!');
        } 
            
        
    }
    
    /**
     * Создаем объект класса Request
     */
    private function initRequest()
    {
        $this->request = new Request($_GET, $_POST, $_FILES, new Cookie(), $_SERVER, new Session());
    }

    /**
     * Обработка запроса в адресной строке
     */
    private function handlingUri()
    {
        $arr = $this->getUriAsArr();

        $this->setIdFromUri($arr);
        $this->controller = $this->getController($arr);
        $this->action = $this->getAction($arr);
    }

    /**
     * Получение класса контроллера
     * @param array $uri URL отформатированный в массив
     * 
     * @return string
     */
    private function getController(array $uri)
    {
        $isAdmin = false;
	$path = 'client\\';      
        $controller = $uri[0] ?? DEFAULT_CONTROLLER;

        if ($controller === 'admin') {
            $isAdmin = true;
            $controller = $uri[1] ?? DEFAULT_CONTROLLER;
        }
        
        

        switch ($controller) {
            case 'post':
            case 'posts':
            case 'post_edit':
            case 'add':
            case 'delete':
                $controller = 'Post';
                break;
            case 'login':
            case 'unlogin':    
            case 'reg':
            case 'users':
            case 'user':
            case 'user_edit':
            case 'user_add':
            case 'user_delete':
            case 'privs':
                $controller = 'User';
                break;

            case 'contacts':
                $controller = 'Page';
                break;
            default:
                $controller = false;
                break;
        }
        
        if ($isAdmin) {
            $controller = "Admin{$controller}";
            $path = 'admin\\';
        }
        
        return $controller ? sprintf('controller\%sController', $path.$controller) : false;
    }

    /**
     * Получение метода контроллера
     * @param array $uri URL отформатированный в массив
     * 
     * @return string
     */
    private function getAction(array $uri)
    {
        if (isset($uri[0]) && $uri[0] === 'admin'){
            return sprintf('%sAction', $uri[1] ?? 'index');
        }
        
        return sprintf('%sAction', $uri[0] ?? 'index');
    }

    /**
     * Преобразование строки запроса клиента в массив
     * 
     * @return array
     */
    private function getUriAsArr()
    {
        $uri = explode('/', $this->request->get['q']);
        $uri_cnt = count($uri);

        if ($uri[$uri_cnt - 1] == '') {
            unset($uri[$uri_cnt - 1]);
        }
        return $uri;
    }

    /**
     * Получение идентификатора запрашиваемой страницы
     * @param array $uri URL отформатированный в массив
     * 
     * @return integer get['id']
     */
    private function setIdFromUri(array $uri)
    {
        if (isset($uri[0]) && $uri[0] === 'admin' && isset($uri[2])){
            return $this->request->get['id'] = $uri[2];
        }
        
        if (isset($uri[1])) {
            return $this->request->get['id'] = $uri[1];
        }
    }

}
