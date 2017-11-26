<?php


/**
 * PageController
 *
 * @author bumer
 */

namespace controller\client;

use core\Request;
use core\ServiceContainer;

class PageController extends PublicController  {
  
    public function __construct(Request $request, ServiceContainer $container)
    {
        parent::__construct($request, $container);
        $this->user = $this->container->get('service.user', [$this->request]);   
        $this->login = $this->user->isAuth() ?: "Гость";
               
    }  
    
    public function contactsAction ()
    {
       
        $this->content = $this->template('view_contact');
    
        $this->title = 'Контакты';

    }
    
   
    
}
