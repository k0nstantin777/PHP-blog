<?php

/**
 * BaseController
 *
 * @author bumer
 */

namespace controller;

use core\User,
    core\Request,
    core\ArrayHelper,
    core\exception\PageNotFoundException;

class BaseController
{

    protected $title;
    protected $content;
    protected $aside;
    protected $user = 'Гость';
    protected $login;
    protected $menu;
    protected $request;

    public function __construct(Request $request)
    {
        $this->title = '';
        $this->content = '';
        $this->menu = $this->template('view_menu', [
            'login' => $this->login
        ]);
                       
        $this->request = $request;
        if (!User::auth($this->request->session, $this->request->cookie)) {
            $this->login = false;
        } else {
            $this->login = true;
            $this->user = ArrayHelper::get($_SESSION, 'login');
        }
    
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
            'user' => $this->user,
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
