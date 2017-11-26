<?php

namespace core\router;

use core\ResponseInterface;
use core\exception\PageNotFoundException;

class Router
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_HEAD = 'HEAD';
    const METHOD_PATCH = 'PATCH';

    private $storage = [
        self::METHOD_GET => [],
        self::METHOD_POST => [],
        self::METHOD_DELETE => [],
        self::METHOD_PUT => [],
        self::METHOD_HEAD => [],
        self::METHOD_PATCH => [],
    ];

    private $parser;
    private $response;

    public function __construct(RouteParser $routerParser, ResponseInterface $response)
    {
        $this->parser = $routerParser;
        $this->response = $response;
       
    }

    /**
     * Регистрация маршрутов при заходе методом GET
     * @param string $route
     * @param string $action
     */
    public function get(string $route, $action)
    {
        $method = self::METHOD_GET;

        $this->parser->handleRoute($route);

        $this->storage[$method][$this->parser->clean]['action'] = $action;
        $this->storage[$method][$this->parser->clean]['params'] = $this->parser->params;
    }

    /**
     * Регистрация маршрутов при заходе методом POST
     * @param string $route
     * @param string $action
     */
    public function post(string $route, $action)
    {
        $method = self::METHOD_POST;
        $this->parser->handleRoute($route);

        $this->storage[$method][$this->parser->clean]['action'] = $action;
        $this->storage[$method][$this->parser->clean]['params'] = $this->parser->params;
    }

    /**
     * Запуск обработчика маршрутов
     * @param string $method
     * @param string $uri
     * @return \Closure
     * @throws PageNotFoundException
     */
    public function run(string $method, string $uri)
    {
        $methodRoutes = $this->storage[$method];
        
        $route = $this->matching($uri, $methodRoutes);

        if (!$route) {
            throw new PageNotFoundException();
        }
        
        if ($route['action'] instanceof \Closure){
            return call_user_func_array($route['action'], $route['params']);
        }
            
        return $route['action'];
    }

    /**
     * Установка маршрутов сайта
     */
    public function setRoutes ()
    {
        $routes = [
            0 => [
                'method' => self::METHOD_GET,
                'route' => '',
                'action' => 'post|index'
            ],
            1 => [
                'method' => self::METHOD_GET,
                'route' => 'posts',
                'action' => 'post|posts'
            ],
            2 => [
                'method' => self::METHOD_GET,
                'route' => 'add',
                'action' => 'post|add'
            ],
            3 => [
                'method' => self::METHOD_GET,
                'route' => 'post/{id}',
                'action' => function ($id){
                                $this->response->send(DEFAULT_CONTROLLER, 'post', ['id' => $id]);
                            }
            ],
            4 => [
                'method' => self::METHOD_GET,
                'route' => 'contacts',
                'action' => 'page|contacts'
            ],
            5 => [
                'method' => self::METHOD_GET,
                'route' => 'login',
                'action' => 'user|login'
            ],
            6 => [
                'method' => self::METHOD_POST,
                'route' => 'login',
                'action' => 'user|login'
            ],
            7 => [
                'method' => self::METHOD_GET,
                'route' => 'reg',
                'action' => 'user|reg'
            ],
            8 => [
                'method' => self::METHOD_GET,
                'route' => 'admin',
                'action' => 'admin.post|index'
            ],
            9 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/users',
                'action' => 'admin.user|users'
            ],
            10 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/posts',
                'action' => 'admin.post|posts'
            ],
            11 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/post_edit/{id}',
                'action' => function ($id){
                                $this->response->send('admin.post', 'edit', ['id' => $id]);
                            }
            ],
            12 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/user_edit/{id}',
                'action' => function ($id){
                                $this->response->send('admin.user', 'edit', ['id' => $id]);
                            }
            ],
            13 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/user/{id}',
                'action' => function ($id){
                                $this->response->send('admin.user', 'user', ['id' => $id]);
                            }
            ],
            14 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/user_add',
                'action' => 'admin.user|add'
            ],
            15 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/privs',
                'action' => 'admin.user|privs'
            ],
            16 => [
                'method' => self::METHOD_POST,
                'route' => 'admin/privs',
                'action' => 'admin.user|privs'
            ],
            17 => [
                'method' => self::METHOD_POST,
                'route' => 'admin/user_add',
                'action' => 'admin.user|add'
            ],
            18 => [
                'method' => self::METHOD_POST,
                'route' => 'admin/user_edit/{id}',
                'action' => function ($id){
                                $this->response->send('admin.user', 'edit', ['id' => $id]);
                            }
            ],
            19 => [
                'method' => self::METHOD_POST,
                'route' => 'admin/post_edit/{id}',
                'action' => function ($id){
                                $this->response->send('admin.post', 'edit', ['id' => $id]);
                            }
            ],
            20 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/add',
                'action' => 'admin.post|add'
            ],
            21 => [
                'method' => self::METHOD_POST,
                'route' => 'admin/add',
                'action' => 'admin.post|add'
            ],
            22 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/delete/{id}',
                'action' => function ($id){
                                $this->response->send('admin.post', 'delete', ['id' => $id]);
                            }
            ],
            23 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/user_delete/{id}',
                'action' => function ($id){
                                $this->response->send('admin.user', 'delete', ['id' => $id]);
                            }
            ],
            24 => [
                'method' => self::METHOD_POST,
                'route' => 'add',
                'action' => 'post|add'
            ],
            25 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/post/{id}',
                'action' => function ($id){
                                $this->response->send('admin.post', 'post', ['id' => $id]);
                            }
            ],
            26 => [
                'method' => self::METHOD_GET,
                'route' => 'unlogin',
                'action' => 'user|unlogin'
            ],
            27 => [
                'method' => self::METHOD_GET,
                'route' => 'admin/login',
                'action' => 'admin.user|login'
            ],
        ];
        
        for ($i = 0; $i < count($routes); $i++){
            
            if ($routes[$i]['method'] === self::METHOD_GET){
                $this->get($routes[$i]['route'], $routes[$i]['action']);
            }
            
            if ($routes[$i]['method'] === self::METHOD_POST){
                $this->post($routes[$i]['route'], $routes[$i]['action']);
            }
        }
    }
    
    private function matching(string $route, array $routeCollection)
    {
        $paramsValue = [];

        while (!isset($routeCollection[$route])) {

            if ($route === '__') {
                break;
            }
                        
            $route = str_replace('__', '', $route);

            $routeParts = explode('/', $route);

            $paramsValue[] = array_pop($routeParts);

            $route = implode('/', $routeParts);
            $route .= '__';
        }

        $matched = $routeCollection[$route] ?? false;

        if (!$matched) {
            return $matched;
        }

        $paramsValue = array_reverse($paramsValue);
        
        $matched['params'] = $this->setValueToParam($matched['params'], $paramsValue);

        return $matched;
    }

    private function setValueToParam(array $paramsName, array $paramsValue)
    {
        $res = [];
        for ($i = 0; $i < count($paramsName); $i++) {
            
            $res[$paramsName[$i]] = $paramsValue[$i];
        }
         
        return $res;
    }
}