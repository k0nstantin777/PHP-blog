<?php


/**
 * PageController
 *
 * @author bumer
 */

namespace controller;
use controller\BaseController;

//include_once __DIR__ . '/BaseController.php';


class PageController extends BaseController  {
  
    public function contactsAction ()
    {
        $this->content = $this->template('view_contact');
    
        $this->title = 'Контакты';
        $this->menu = $this->template('view_menu', [
            'login'  => false
        ]);
    }        
    
}
