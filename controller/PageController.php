<?php


/**
 * PageController
 *
 * @author bumer
 */

namespace controller;

class PageController extends BaseController  {
  
    public function contactsAction ()
    {
        $this->content = $this->template('view_contact');
    
        $this->title = 'Контакты';

    }
    
   
    
}
