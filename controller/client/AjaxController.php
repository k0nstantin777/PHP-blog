<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controller\client;

use core\Request;
use core\ServiceContainer;
use core\helper\ArrayHelper;
use core\exception\ValidatorException;

/**
 * Description of AjaxController
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class AjaxController extends PublicController
{
    public function __construct(Request $request, ServiceContainer $container)
    {
        parent::__construct($request, $container);
                      
    } 
    
    public function checkLoginAction ()
    {
        sleep(1.5);
        
        if (!isset($this->request->post['login'])){
            return;
        }
        $mUser = $this->container->get('model.user');
        
        try { 
            $mUser->getValidFields($this->request->post);
        } catch (ValidatorException $e) {
           echo $e->getErrors()['login']; 
           return;
        }
        
        $user = $mUser->getByLogin($this->request->post['login']);
       
        if (!$user){
            echo 'Пользователя не существует';
            return;
        }
        
        echo $user['login'];
        return;
    }
    
    public function response()
    {
        //обнуляем подгрузку шаблона
    }
    
}
