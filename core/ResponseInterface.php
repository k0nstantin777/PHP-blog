<?php

namespace core;

/**
 * Ответ клиенту
 * @author bumer
 */
interface ResponseInterface
{
    /**
     * Конструктор 
     * 
     * @param string $file_template имя файла шаблона.php
     * @param array $vars переменные отправляеме в шаблон
     * @param string $header заголовок
     * @param string $status статуса ответа
     */
    public function __construct($file_template, array $vars = [], $header = '', $status = 'HTTP/1.1 200 Ok');
    
    /**
     * Подключение шаблона
     * 
     * @return string шаблон страницы
     */
    public function Template();
    
    /**
     * Установка заголовков ответа сервера
     * 
     * @return string
     */
    public function SetHeader();
    
    /**
     * Установка статусов ответа сервера
     * 
     * @return string
     */
    public function SetStatus();
    
    /**
     * Установка параметров Cookie
     * @param string $params
     * 
     * @return string
     */
    public function SetCookie(array $params);
    
    
    /**
     * Установка параметров Session
     * @param string $params
     * 
     * @return string
     */
    public function SetSession(array $params);
    
    /**
     * Отправка ответа клиенту
     *  
     * @return string
     */
    public function SendResponse();
}
