<?php


/**
 * PageController
 *
 * @author bumer
 */

namespace controller\client;

use core\Request;
use core\ServiceContainer;
use core\helper\ArrayHelper;

class PageController extends PublicController  {
  
    public function __construct(Request $request, ServiceContainer $container)
    {
        parent::__construct($request, $container);
        $this->user = $this->container->get('service.user', [$this->request]);   
        $this->login = $this->user->isAuth() ?: "Гость";
               
    }  
    
    /**
     * Шаблон страницы Контакты (/contacts)
     */
    public function contactsAction ()
    {
       
        $this->content = $this->template('view_contact');
    
        $this->title = 'Контакты';

    }
    
    /**
     * вывод сообщений об ошибках
     * @param string $msg
     * @param string $title
     * @param string $view_template
     */
    public function errorAction($msg, $title, $view_template = '404')
    {
        $this->title = $title;
        $this->menu = '';
        $this->content = $this->template($view_template, 
                                                         [
                                                            'msg' => $msg,
                                                            'back' => ArrayHelper::get($this->request->server, 'HTTP_REFERER', BASE_PATH),        
                                                         ]);
        $this->login = $this->request->session->get('login') ?: 'Гость';
    }
    
}
