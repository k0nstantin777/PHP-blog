<?php

namespace core;

use core\exception\PageNotFoundException,
    core\exception\BaseException,
    core\Logger,
    controller\BaseController,
    core\Request;

/**
 * Application
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class Application
{

    public $request;
    private $controller;
    private $action;

    public function __construct()
    {
        $this->initRequest();
        $this->handlingUri();
    }

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
             // TODO определить режим работы сайта
            if (!DEVELOP) {
                $this->errorSend($e, 'critical', 'ERROR', 'Ooooops... Something went wrong!');
            } else {
                die(nl2br(
                'Filed connect to DB' . PHP_EOL
                . 'Message: ' . $e->getMessage() . PHP_EOL
                . 'Trace: ' . PHP_EOL . $e->getTraceAsString()
                ));
            }
        } catch (PageNotFoundException $e) {
            if (!DEVELOP) {
                $ctrl = new BaseController($this->request);
                $ctrl->er404Action('Error 404 - Page not found!');
                $ctrl->response();
            } else {
                
            }
        } catch (\Exception $e) {

            $logger = new Logger('critical', LOG_DIR);
            $logger->write(sprintf("%s\n%s", $e->getMessage(), $e->getTraceAsString()), 'ERROR');

            // TODO определить режим работы сайта

            $ctrl = new BaseController($this->request);
            $ctrl->er404Action('Ooooops... Something went wrong!');
            $ctrl->response();
        }
    }

    private function errorSend($exception, $filename, $level, $msg)
    {
        $logger = new Logger($filename, LOG_DIR);
        $logger->write(sprintf("%s\n%s", $exception->getMessage(), $exception->getTraceAsString()), $level);
        $ctrl = new BaseController($this->request);
        $ctrl->er404Action($msg);
        $ctrl->response();
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
        return sprintf('%sAction', $uri[0] ?? DEFAULT_ACTION);
    }

    /**
     * Преобразование строку запроса клиента в массив
     * 
     * @return array
     */
    private function getUriAsArr()
    {
        $uri = explode('/', $this->request->getParam($this->request->get['q']));
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
