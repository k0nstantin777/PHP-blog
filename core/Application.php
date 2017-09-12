<?php

namespace core;

use core\exception\PageNotFoundException,
    core\exception\AccessException,
    core\ServiceContainer,
    core\providers\ModelProvider,
    core\providers\UserProvider,
    core\providers\ErrorHandlerProvider,
    core\providers\ControllerProvider,
    core\providers\RouterProvider,
    core\providers\ResponseProvider,
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

    private $container;
    
    /**
     * router
     * @var type 
     */
    private $router;
    
    private $response;

    public function __construct()
    {
        $this->initRequest();
        
        $this->container = new ServiceContainer($this->request);
        (new ControllerProvider())->register($this->container);
        (new RouterProvider())->register($this->container);
        (new ResponseProvider())->register($this->container);
        (new UserProvider())->register($this->container);
        (new ModelProvider())->register($this->container); 
        (new ErrorHandlerProvider())->register($this->container);
        $this->response = $this->container->get('module.response', [$this->request]);
        $this->router = $this->container->get('module.router', [$this->response]);
        $this->router->setRoutes();
    }

    /**
     * Запуск блога
     * 
     */
    public function run()
    {
        try {
    
            $route = $this->router->run($this->request->server['REQUEST_METHOD'], $this->request->get['q']);
            
            if ($route){
                $arr = explode('|', $route);
                $this->response->send ($arr[0], $arr[1]);
            }
                        
        } catch (\PDOException $e) {
            $ctrl = $this->container->get('controller.public');
            $this->container->get('errorHandler.logger')->handle($ctrl, $e, 'Ooooops... Something went wrong!');
            
        } catch (PageNotFoundException $e) {
            $this->container->get('errorHandler.screen', [$this->response])->handle($e, 'Error 404 - Page not found!');
             
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
}
