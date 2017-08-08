<?php

namespace core;

use core\helper\Cookie,
    core\helper\Session;

/**
 * Request
 *
 * @author bumer
 */
class Request
{

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /**
     * Глобальный массив $_GET
     * @var array
     */
    public $get;
    /**
     * Глобальный массив $_POST
     * @var array
     */
    public $post;
    /**
     * Глобальный массив $_FILE
     * @var array
     */
    public $file;
    /**
     * Глобальный массив $_COOKIE
     * @var array
     */
    public $cookie;
    /**
     * Глобальный массив $_SERVER
     * @var array
     */
    public $server;
    /**
     * Глобальный массив $_SESSION
     * @var array
     */
    public $session;

    public function __construct($get, $post, $file, Cookie $cookie, $server, Session $session)
    {
        $this->get = $get;
        $this->post = $post;
        $this->file = $file;
        $this->cookie = $cookie;
        $this->server = $server;
        $this->session = $session;
    }

    /**
     * Проверка захода на страницу методом POST
     * 
     * @return true
     */
    public function isPost()
    {
        return $this->server['REQUEST_METHOD'] === self::METHOD_POST;
    }

    /**
     * Проверка захода на страницу методом GET
     * 
     * @return true
     */
    public function isGet()
    {
        return $this->server['REQUEST_METHOD'] === self::METHOD_GET;
    }
        
}
