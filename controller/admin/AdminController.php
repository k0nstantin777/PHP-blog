<?php

/**
 * Description of AdminController
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
namespace controller\admin;

use core\Request;
use core\ServiceContainer;
use controller\BaseController;

class AdminController extends BaseController
{
    public function __construct(Request $request, ServiceContainer $container)
    {
        parent::__construct($request, $container);
        $this->user_prives = $this->request->session->get('prives') ?? [];
        $this->user = $this->container->get('service.user', [$this->request]);   
        $this->login = $this->user->isAuth() ?: "Гость";
        $this->aside = $this->template('admin/view_menu');
    }

    public function response()
    {
        echo $this->template(
                'admin/view_main',
                [
                    'title' => $this->title,
                    'content' => $this->content,
                    'login' => $this->login,
                    'prives' => $this->user_prives,
                    'aside' => $this->aside
                ]
        );
    }
}
