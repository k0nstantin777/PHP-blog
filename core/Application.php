<?php

namespace core;

use core\exception\PageNotFoundException,
    core\exception\ValidatorException,
    controller\BaseController;
    

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

    public function __construct()
    {
        $this->initRequest();
        $this->handlingUri();
    }

    /**
     * Запуск блога
     * 
     * @throws PageNotFoundException
     * 
     */
    public function run()
    {
        try {

            if (!$this->controller) {
                throw new PageNotFoundException();
            }

            $ctrl = new $this->controller($this->request);

            $action = $this->action;

            $ctrl->$action();

            $ctrl->response();
        } catch (\PDOException $e) {
            (
                new ErrorHandler(
                    new BaseController($this->request), 
                    new Logger('critical', LOG_DIR),
                    DEVELOP
                )
            )->handle($e, 'Ooooops... Something went wrong!');
            
        } catch (PageNotFoundException $e) {
            (
                new ErrorHandler(
                    new BaseController($this->request),
                    null,
                    DEVELOP
                )
            )->handle($e, 'Error 404 - Page not found!');
            
        } catch (\Exception $e) {
            (
                new ErrorHandler(
                    new BaseController($this->request), 
                    new Logger('critical', LOG_DIR),
                    DEVELOP
                )
            )->handle($e, 'Ooooops... Something went wrong!');
        }
    }
    
    /**
     * Создаем объект класса Request
     */
    private function initRequest()
    {
        $this->request = new Request($_GET, $_POST, $_FILES, $_COOKIE, $_SERVER, $_SESSION);
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
        $controller = $uri[0] ?? DEFAULT_CONTROLLER;

        switch ($controller) {
            case 'post':
            case 'posts':
            case 'edit':
            case 'add':
            case 'delete':
                $controller = 'Post';
                break;
            case 'login':
            case 'unlogin':    
            case 'reg':
                $controller = 'User';
                break;

            case 'contacts':
                $controller = 'Page';
                break;
            default:
                $controller = false;
                break;
        }

        return $controller ? sprintf('controller\%sController', $controller) : false;
    }

    /**
     * Получение метода контроллера
     * @param array $uri URL отформатированный в массив
     * 
     * @return string
     */
    private function getAction(array $uri)
    {
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
        if (isset($uri[1])) {
            $this->request->get['id'] = $uri[1];
        }
    }

}
