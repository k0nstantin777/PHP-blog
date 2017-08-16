<?php

/**
 * BaseController
 *
 * @author bumer
 */

namespace controller;

use core\Request,
    core\helper\ArrayHelper,
    core\ServiceContainer,
    core\exception\PageNotFoundException;

class BaseController
{

    protected $title;
    protected $content;
    protected $aside;
    protected $user;
    protected $login;
    protected $menu;
    protected $request;
    protected $priv_name;
    protected $user_prives = [];
    protected $container;



    public function __construct(Request $request, ServiceContainer $container)
    {
        $this->container = $container;
        $this->request = $request;
        $this->title = '';
        $this->content = '';
        
        $this->user_prives = $this->request->session->get('prives') ?? [];

        $this->user = $this->container->get('service.user', [$this->request]);   
        if (!$this->user->isAuth()) {
            $this->login = "Гость";
        } else {
            $this->login = $this->request->session->get('login');
        }
        
        $this->menu = $this->template('view_menu', [
           'prives' => $this->user_prives,
        ]);       
        

    }

    public function __call($name, $args)
    {
        throw new PageNotFoundException();
    }

    /* вывод шаблона страницы в браузере */

    public function response()
    {
        echo $html = $this->template('view_main', [
            'title' => $this->title,
            'content' => $this->content,
            
            'login' => $this->login,
            'aside' => $this->aside,
            'menu' => $this->menu
        ]);
    }

    /* ошибка 404 */

    public function er404Action($msg)
    {
        $this->title = 'Ошибка 404';
        $this->content = $this->template('404', ['msg' => $msg]);
        header("HTTP/1.1 404 Not Found");
    }

    /* подключение шаблона страницы */

    protected function template($path, $vars = [])
    {
        extract($vars);
        ob_start();
        include("view/$path.php");
        return ob_get_clean();
    }

}
