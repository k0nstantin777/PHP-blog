<?php

/**
 * UserController
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */

namespace controller\admin;

use core\Request;
use core\ServiceContainer;
use core\exception\UserException;
use core\exception\PageNotFoundException;
use core\helper\ArrayHelper;


class AdminUserController extends AdminController {
         
    /**
     * Объект класса UserModel
     * @var object 
     */
    public $mUser;
     
    public function __construct(Request $request, ServiceContainer $container)
    {
        parent::__construct($request, $container);
        $this->user = $this->container->get('service.user', [$this->request]);   
        $this->login = $this->user->isAuth() ?: "Гость";
        $this->mUser = $this->container->get('model.user');
        
    }   
    
    
    /* авторизация */
    public function loginAction()
    {
        /* Страница авторизации */
        $msg = '';
        $errors = [];
                        
        //проверка на POST или GET
        if ($this->request->isPost()) {
           
            try {
                $this->user->login();     
                header("Location:". BASE_PATH . 'admin/');
                exit();
            } catch (UserException $e) {
                $errors = $e->getErrors();
                $msg = $e->getMessage();
            }
        }
          
        $this->content = $this->template('admin/view_login', [
            'errors'  => $errors,
            'post' => $this->request->post,
            'msg' => $msg,
            'login' => $this->login
        ]);

        $this->title = 'Aдминка.Авторизация';
    }
    
    /* регистрация нового пользователя */
    public function user_addAction()
    {
        $this->priv_name = 'add_user';
        
        $errors = [];
        $msg = '';
        
        if($this->request->isPost()){
            
            try {
                $this->user->registration();     
                header("Location: ".BASE_PATH. "admin/users?success=reg");
                exit();
            } catch (UserException $e) {
                $errors = $e->getErrors();
                $msg = $e->getMessage();
            } 
                
        } 

        $this->content = $this->template('admin/view_user_add', [
            'errors'  => $errors,
            'post' => $this->request->post,
            'msg' => $msg,
            'login' => $this->login,
            'get' => $this->request->get,
            'back' => ArrayHelper::get($this->request->server, 'HTTP_REFERER', BASE_PATH),
            'roles' => $this->container->get('model.role')->getAll()
        ]);
        $this-> title = 'Регистрация нового пользователя'; 
    }
    
    public function unloginAction ()
    {
        $this->user->unLogin();
        $this->login = 'Гость';
        header("Location: ".BASE_PATH);
        exit();
    }
  
    /**
     *  страница вывода всех пользователей admin/users 
     */
    public function usersAction()
    {
        $this->priv_name = 'access_admin_console';
        if (!$this->user->can($this->priv_name)) {
            header("Location:". BASE_PATH . 'admin/login');
        }
        
        /* вывод сообщения после выполнения действия */
        $msg = '';
        
        if ($this->login !== 'Гость') {
            $success = isset($this->request->get['success']) ? $this->request->get['success'] : '';
            $msgs = ['edit' => 'Изменения сохранены', 'reg' => 'Пользователь добавлен', 'delete' => 'Пользователь удален'];
            $msg = isset($msgs[$success]) ? $msgs[$success] : '';
        }
        $this->content = $this->template('admin/view_users', [
            'users' => $this->mUser->getAll(),
            'prives' => $this->user_prives,
            'msg' => $msg,
        ]);
        $this->title = 'Пользователи';
    }
    
    /* страница одного пользователя admin/user/<id> */

    public function userAction()
    {
        $this->priv_name = 'access_admin_console';
        if (!$this->user->can($this->priv_name)) {
            header("Location:". BASE_PATH . 'admin/login');
        }
        
        $id = $this->request->get['id'];
        $user = $this->mUser->getOne($id);
        if (!$user){
            throw new PageNotFoundException ();
        }     
        $this->content = $this->template('admin/view_user', [
                'user' => $user,
                'back' => ArrayHelper::get($this->request->server, 'HTTP_REFERER', BASE_PATH),
                'prives' => $this->user_prives,
                'role'=> $this->mUser->getRoleByLogin($user['login']),
        ]);
        $this->title = $user['login'];
        
    }
    
    /* страница редактрирование статьи /edit/id */
    public function user_editAction()
    {
        $this->priv_name = 'edit_user';

        if (!$this->user->can($this->priv_name)) {
            throw new AccessException ();
        }
              
        $id = $this->request->get['id'];
               
        $user = $this->mUser->getOne($id);
        if (!$user){
            throw new PageNotFoundException ();
        } 
        
        $errors = [];
        $msg = '';
          
        // обработка формы методом POST 
        if ($this->request->isPost()) {
            
            try {    
                $this->user->edit($user);
                header("Location:" . BASE_PATH . "admin/users?success=edit");
                exit();
            } catch (UserException $e) {
                $errors = $e->getErrors();
                $msg = $e->getMessage();
            }
        } 

       
        $this->content = $this->template('admin/view_user_edit', [
                'name' => ArrayHelper::get($this->request->post, 'login', $user['login']),
                'password' => ArrayHelper::get($this->request->post, 'password', $user['password']),
                'new_password' => ArrayHelper::get($this->request->post, 'new_password', ''),
                'back' => ArrayHelper::get($this->request->server, 'HTTP_REFERER', BASE_PATH),
                'errors'=> $errors,
                'msg' => $msg,
                'id' => $id,
                'role'=> $this->mUser->getRoleByLogin($user['login']),
                'roles' => $this->user->getFields()['roles']
                
        ]);
        $this->title = 'Изменить данные пользователя';
        
    }
    
    public function user_deleteAction()
    {
        $this->priv_name = 'delete_user';
        
        if (!$this->user->can($this->priv_name)) {
            throw new AccessException ();
        }
        
        $id = $this->request->get['id'];
       
        //удалеяем юзера
        if (!$this->mUser->delete($id)) {
            throw new PageNotFoundException ();
        }
        header("Location: " . BASE_PATH . "admin/users?success=delete");
        exit();
    }
    
    public function privsAction()
    {
        $this->priv_name = 'edit_prive';
        if (!$this->user->can($this->priv_name)) {
            throw new AccessException ();
        }
        
        $fields = $this->user->getFields();
               
        if ($this->request->isPost()) {
            
            $this->user->setPrives();
            header("Location:" . BASE_PATH . "admin/users?success=edit");
            exit();
            
        } 

        $this->content = $this->template('admin/view_prives_edit', [
                  'roles' => $fields['roles'],
                  'privs_to_roles' => $fields['privs_to_roles'],
                  'privs' => $fields['privs'],
                  'back' => ArrayHelper::get($this->request->server, 'HTTP_REFERER', BASE_PATH),
                
        ]);
        $this->title = 'Изменение прав';
    }
}
