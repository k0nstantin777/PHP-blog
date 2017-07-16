<?php

/**
 * UserController
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */

namespace controller;

use controller\BaseController,
    model\UserModel,
    model\SessionModel,
    core\Core,
    core\DB,
    core\User,    
    core\DBDriver,
    core\Request,
    core\Validator, 
    core\exception\ValidatorException,
    core\exception\UserException,    
    core\exception\PageNotFoundException;


class UserController extends BaseController {
         
    /**
     * Объект класса UserModel
     * @var object 
     */
    public $mUser;
     
    /**
     * Объект класса User
     * @var object 
     */
    public $userObj;
    
    public $mSession;


     public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->mUsers = new UserModel(new DBDriver(DB::get()), new Validator());
        $this->mSession = new SessionModel(new DBDriver(DB::get()), new Validator());
        $this->userObj = new User($this->mUsers, $this->mSession);
    }   
    
    
    /* авторизация */
    public function loginAction()
    {
        /* Страница авторизации */
        $msg = '';
        $errors = [];
        $this->mUsers->setSchema(['login', 'password']);
        //проверка на POST или GET
        if ($this->request->isPost()) {
            
            try {
                $this->userObj->login($this->request->post, $this->request->session, $this->request->cookie);     
                header("Location:". BASE_PATH . 'posts');
                exit();
            } catch (UserException $e) {
                $errors = $e->getErrors();
                $msg = $e->getMessage();
            }

        }
          
        $this->content = $this->template('view_login', [
            'errors'  => $errors,
            'post' => $this->request->post,
            'msg' => $msg,
            'login' => $this->login
        ]);

        $this->title = 'Авторизация';
    }
    
    /* регистрация нового пользователя */
    public function regAction()
    {
        $errors = [];
        $msg = '';
        $this->mUsers->setSchema(['login', 'password']);
        if($this->request->isPost()){
            
            try {
                $this->userObj->registration($this->request->post);     
                header("Location: ".BASE_PATH. "reg?success=reg");
                exit();
            } catch (UserException $e) {
                $errors = $e->getErrors();
                $msg = $e->getMessage();
            }
                
        } 

        $this->content = $this->template('view_reg', [
            'errors'  => $errors,
            'post' => $this->request->post,
            'msg' => $msg,
            'login' => $this->login,
            'get' => $this->request->get
        ]);
        $this-> title = 'Регистрация'; 
    }
    
    public function unloginAction ()
    {
        $this->userObj->unLogin($this->user);
        $this->user = 'Гость';
        $this->login = false;
        unset($this->request->session['auth']);
        unset($this->request->session['login']);
        Core::deleteCookie('login');
        Core::deleteCookie('password');
        header("Location: ".BASE_PATH);
        exit();
    }
    
    
    
}
