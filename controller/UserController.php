<?php

/**
 * UserController
 *
 * @author bumer
 */

namespace controller;
use controller\BaseController,
    model\UserModel,
    core\Core,
    core\DB,
    core\DBDriver,
    core\Request;


class UserController extends BaseController {
         
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->mUsers = new UserModel(new DBDriver(DB::get()));
    }   
    
    
    /* авторизация */
    public function loginAction()
    {
        /* Страница авторизации */
        $msg = '';
        //проверка на POST или GET
        if ($this->request->isPost()) {
            $login = $this->request->getParam($this->request->post['login']);
            $password = $this->request->getParam($this->request->post['password']);
            $userLogin = $this->mUsers->getOne($login);
            if($login == '' || $password == ''){
                $msg = 'Заполните все поля'; 
            } elseif ($login != '' && $userLogin === false){
                $msg = 'Пользователя с таким логином не существует';
            } else {
                if($login == $userLogin['login'] && Core::myCrypt($password) == $userLogin['password']){
                    $_SESSION['auth'] = true;
                    $_SESSION['login'] = $userLogin['login'];

                    if($this->request->getParam($this->request->post['remember'])){
                        setcookie('login', $userLogin['login'], time() + 3600 * 24 * 365);
                        setcookie('password', $userLogin['password'], time() + 3600 * 24 * 365);
                    }
                    header("Location:". BASE_PATH . 'posts');
                    exit();
                } else {
                    $msg = 'Неправильный пароль!';
                }
            }     
        } else {
            
            unset($_SESSION['auth']);
            Core::deleteCookie('login');
            Core::deleteCookie('password');
            $this->login = false;
            $this->user = 'Гость';
            $success = isset($this->request->get['success']) ? $this->request->getParam($this->request->get['success']) : '';
            $msgs = ['reg' => 'Регистрация прошла успешно, теперь вы можете авторизоваться'];
            $msg = isset($msgs[$success]) ? $msgs[$success] : '';
 
        }
    
        $this->content = $this->template('view_login', [
            'msg'  => $msg
        ]);

        $this->title = 'Авторизация';
    }
    
    /* регистрация нового пользователя */
    public function regAction()
    {
        $msg = '';
        if($this->request->isPost()){
            $login = $this->request->getParam($this->request->post['login']);
            $password = $this->request->getParam($this->request->post['password']);
            if($login == '' || $password == ''){
                $msg = 'Заполните все поля';
            } elseif (!Core::checkName($login, 'user')){ 
                $msg = 'Запрещенные символы в поле "Логин"'; 
            } elseif ($this->mUsers->getOne($login)!==false){
                $msg = 'Логин занят!'; 
            } else {
                $this->mUsers->add (['login'=> $login, 'password' => Core::myCrypt($password)]);     
                header("Location: ".BASE_PATH. "login?success=reg");
                exit();
            } 
        } else {
            /* зашли на страницу методом GET */
            $login = '';
            $password = '';
            $msg = '';
        }

        $this->content = $this->template('view_reg', [
            'back' => $this->request->getParam($this->request->server['HTTP_REFERER']),
            'msg'  => $msg,
            'login' => $login
        ]);
        $this-> title = 'Регистрация'; 
    }
    
    
}
