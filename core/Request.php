<?php

namespace core;

/**
 * Description of Request
 *
 * @author bumer
 */
class Request
{

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /**
     * Глобальный масси $_GET
     * @var array
     */
    public $get;
    /**
     * Глобальный масси $_POST
     * @var array
     */
    public $post;
    /**
     * Глобальный масси $_FILE
     * @var array
     */
    public $file;
    /**
     * Глобальный масси $_COOKIE
     * @var array
     */
    public $cookie;
    /**
     * Глобальный масси $_SERVER
     * @var array
     */
    public $server;
    /**
     * Глобальный масси $_SESSION
     * @var array
     */
    public $session;

    public function __construct($get, $post, $file, $cookie, $server, $session)
    {
        $this->get = $get;
        $this->post = $post;
        $this->file = $file;
        $this->cookie = $cookie;
        $this->server = $server;
        $this->session = $session;
    }

    /**
     * Проверка захода на страницу методом GET
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
        
    /**
     * Получениа параметра из глобального массива
     * @param string $param
     * 
     * @return string
     */
    public function getParam($param)
    {
        return isset($param) ? trim(htmlspecialchars($param)) : '';
    }  
    
    /**
     * Получение класса контроллера
     * 
     * @return string
     */
    public function getController()
    {
        $controller = isset($this->getHttp()[0]) ? $this->getHttp()[0] : 'post';
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
                        break;
            }
            
        return sprintf('controller\%sController', $controller);
    }
    
    /**
     * Получение метода контроллера
     * 
     * @return string
     */
    public function getAction()
    {
        if ($this->getController() == 'controller\BaseController'){
            return 'er404Action';
        } else {        
            return sprintf('%sAction', isset($this->getHttp()[0]) ? $this->getHttp()[0] : 'index');  
        }    
            
    }        

    /**
     * Получение идентификатора запрашиваемой страницы
     * 
     * @return integer request id
     */
    public function getId()
    {
        return isset($this->getHttp()[1]) ? $this->getHttp()[1] : '';
    }        

    /**
     * Преобразование строку запроса клиента в массив
     * 
     * @return array
     */
    private function getHttp()
    {
        $params = explode('/', $this->getParam($this->get['q']));
        $params_cnt = count($params);

        if($params[$params_cnt - 1] == ''){
            unset($params[$params_cnt - 1]);
        }
        return $params;
    }        
    
    
}
