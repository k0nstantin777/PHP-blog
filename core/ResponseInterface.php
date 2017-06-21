<?php

namespace core;

/**
 * Ответ клиенту
 * @author bumer
 */
interface ResponseInterface
{
    public function __construct();
    
    /**
     * Подключение шаблона
     * @param string $path
     * @param array $vars
     * 
     * @return file шаблон страницы
     */
    public function Template($path, $vars = []);
    
    /**
     * Установка заголовков ответа сервера
     * @param string $header
     * 
     * @return string
     */
    public function SetHeader($header);
    
    /**
     * Установка параметров: сессии, куки
     * @param string $param
     * 
     * @return true
     */
    public function SetParams($param);
    
    /**
     * Отлавливание ошибок и вывод на экран
     * @request string запрос на поиск ошибок
     * 
     * @return string
     */
    public function ErrorView($request);
}
