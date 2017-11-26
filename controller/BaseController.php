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

abstract class BaseController
{

    protected $title;
    protected $content;
    protected $request;
    protected $container;
    protected $login;



    public function __construct(Request $request, ServiceContainer $container)
    {
        $this->container = $container;
        $this->request = $request;
        $this->title = '';
        $this->content = '';
    }

    public function __call($name, $args)
    {
        throw new PageNotFoundException();
    }

    /* вывод шаблона страницы в браузере */

    abstract public function response();

    /* подключение шаблона страницы */

    protected function template($path, $vars = [])
    {
        extract($vars);
        ob_start();
        include("view/$path.php");
        return ob_get_clean();
    }

}
