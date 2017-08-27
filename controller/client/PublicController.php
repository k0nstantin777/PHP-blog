<?php

/**
 * Description of PublicController
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */

namespace controller\client;

use core\Request;
use core\ServiceContainer;
use controller\BaseController;

class PublicController extends BaseController
{
    protected $aside;
    protected $user;
    protected $menu;
    protected $priv_name;
    protected $user_prives = [];
    
    
    public function __construct(Request $request, ServiceContainer $container)
    {
        parent::__construct($request, $container);

        //$this->rightBlock = $this->render('right.html.php');
        $this->user_prives = $this->request->session->get('prives') ?? [];
        $this->menu = $this->template('view_menu', [
           'prives' => $this->user_prives,
        ]); 
        $this->user = $this->container->get('service.user', [$this->request]);   
        $this->login = $this->user->isAuth() ?: "Гость";
    }

    public function response()
    {
        echo $this->template('view_main', 
            [
            'title' => $this->title,
            'content' => $this->content,
            'login' => $this->login,
            'aside' => $this->aside,
            'menu' => $this->menu,
            'prives' => $this->user_prives
            ]
        );
    }
}
