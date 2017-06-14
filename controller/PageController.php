<?php


/**
 * PageController
 *
 * @author bumer
 */

include_once __DIR__ . '/BaseController.php';

class PageController extends BaseController  {
    
    public function contactsAction ()
    {
        $this->content = $this->template('view_contact', [

        ]);
    
        $this->title = 'Контакты';
    }        
    
}
