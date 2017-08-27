<?php

namespace core\module;

/**
 * Модуль работы с User 
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
use model\UserModel,
    model\SessionModel,
    model\PrivModel,
    model\RoleModel,
    model\Privs_to_RolesModel,
    core\exception\UserException,
    core\exception\FormException,
    core\database\DBDriver,
    core\database\DB,
    core\Request,
    core\module\Validator,
    core\helper\ArrayHelper,
    core\exception\ValidatorException;

class User
{

    private $mUser;
    
    private $mSession;
    
    private $mPriv;
    
    private $mRole;
    
    private $mPrives_to_Roles;
       
    private $request;
    
    public function __construct(Request $request, UserModel $mUser, SessionModel $mSession, PrivModel $mPriv, RoleModel $mRole, Privs_to_RolesModel $mPrivs_to_Roles)
    {
        $this->mUser = $mUser;
        $this->mSession = $mSession;
        $this->mPriv = $mPriv;
        $this->mRole = $mRole;
        $this->mPrives_to_Roles = $mPrivs_to_Roles;
        $this->request = $request;
    }

    /**
     * Регистрация пользователя
     * @param array $fields
     * @return void
     * @throws UserException catched in UserController
     */
    public function registration()
    {
        $hash_form = md5('login'.'password'.'role'.'submit');
        if ($hash_form !== md5(implode(array_keys($this->request->post)))){
            throw new UserException([], 'Не пытайтесь подделать форму!');
        }
        
        $user = $this->mUser->getByLogin($this->request->post['login']);
        if (!empty($user) || $this->request->post['login'] === 'Гость') {
            throw new UserException([], sprintf('Пользователь с логином %s уже существует!', $this->request->post['login']));
        }

        $uncryptPass = $this->request->post['password'];
        $password = $this->myCrypt($this->request->post['password']);

        try {
            return $this->mUser->add(
                                      [
                                        'login' => $this->request->post['login'],
                                        'password' => $password,
                                        'uncryptPass' => $uncryptPass,
                                        'id_role'=> $this->request->post['role']
                                      ]);
        } catch (ValidatorException $e) {
            throw new UserException($e->getErrors(), $e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     * Авторизация
     */
    public function login ()
    {
        //валидация формы
        try { 
            $this->mUser->getValidFields($this->request->post);
        } catch (ValidatorException $e) {
            throw new UserException($e->getErrors(), $e->getMessage(), $e->getCode(), $e);
        }
        
        //проверка наличия пользователя
        $userLogin = $this->mUser->getByLogin($this->request->post['login']);
        if (empty($userLogin)) {
            throw new UserException([], sprintf('Пользователя с логином %s не существует!', $this->request->post['login']));
        }
        
        //проверка правильности введенного пароля
        if($this->myCrypt($this->request->post['password']) !== $userLogin['password']) {
            throw new UserException([], 'Неправильный пароль!');
        }    
        
        //устанавливаем параметры сессии
        $this->setSession($userLogin);

        //устанавливаем cookie, если получен флаг remember
        if($this->request->post['remember']){
            $this->request->cookie->set('login', $userLogin['login'], '1 years');
            $this->request->cookie->set('password', $userLogin['password'], '1 years');
        }
 
    }
    
    /**
     * Определение авторизации пользователя
     * @return bool
     */    
    public function isAuth ()
    {
        
        $cookieLogin = $this->request->cookie->get('login');
        $cookiePass = $this->request->cookie->get('password');
        $sid = $this->request->session->get('sid');
        $userLogin = $this->mUser->getByLogin($cookieLogin);
        
                
        if(!$sid && !$userLogin){
            return false;
        }
        
        if ($sid){
            $session = $this->mSession->getBySid($sid);
            $this->mSession->edit(['id' => $session[$this->mSession->id_name], 'updated_at' => date("Y-m-d H:i:s", time())]);
            return $this->request->session->get('login');
        }
        
        if ($userLogin && $userLogin['password'] === $cookiePass) {
            $this->setSession($userLogin);
            return $this->request->session->get('login');
        }
           
    }
    
    /**
     * Проверка привилегий пользователя
     * @param string $prive_name
     * @return string
     */
    public function can($prive_name)
    {
        $login = $this->request->session->get('login');
        return $this->mPriv->getByLoginAndPriveName($login, $prive_name);
    }
    
    /**
     * Установка параметров сессии текущего юзера и удаление устаревших сессий (старше 30 минут)
     * @param array $userLogin строка из таблицы БД users в виде массива
     * @return void
     */
    public function setSession(array $userLogin)
    {
        //получаем токен
        $sid = $this->getToken();
        
        //поиск и удаление устаревших сессий (старше 30 минут) по user_id 
        $this->mSession->delOld($userLogin[$this->mUser->id_name]);
        
        //устанавливаем параметры сессии
        $this->request->session->set('sid', $sid);
        $this->request->session->set('login', ArrayHelper::get($userLogin, 'login'));
        $this->request->session->set('prives', $this->mPriv->getAllByLogin(ArrayHelper::get($userLogin, 'login')));
        $this->mSession->add(['sid' => $sid, 'user_id' => $userLogin[$this->mUser->id_name]]);
    }


    /**
     * Деавторизация
     * @param string $login текущий пользователь
     */
    public function unLogin ()
    {
        //удаляем запись из таблицы session текущего пользователя
        $session = $this->mSession->getBySid($this->request->session->get('sid'));
        $this->mSession->delete($session[$this->mSession->id_name]);
        //удаляем записи cookie и session
        $this->request->session->delete('sid');
        $this->request->session->delete('login');
        $this->request->session->delete('prives');
        $this->request->cookie->delete('login');
        $this->request->cookie->delete('password');
    }
    
    /**
     * Редактирование пользователя
     * @param array $user
     * @return void
     * @throws FormException
     * @throws UserException
     */
    public function edit ($user)
    {
        $hash_form = md5('id'.'login'.'password'.'new_password'.'role'.'submit'.$user['id_user'].$user['password']);
        if ($hash_form !== md5(implode(array_keys($this->request->post)). $this->request->post['id'].$this->request->post['password'] )){
                    throw new FormException('Не пытайтесь подделать форму!');
        }

        $params = [
                    'id_user' => $this->request->post['id'],
                    'login' => $this->request->post['login'],
                    'id_role' => $this->request->post['role']
                  ];
        
        //изменение пароля
        if (!empty($this->request->post['new_password'])){
            $password = $this->myCrypt($this->request->post['new_password']); 
            $uncryptPass = $this->request->post['new_password']; //пароль в чистом виде для валидации
            $params['password'] = $password;
            $params['uncryptPass'] = $uncryptPass;
        }
        
                
        try {
            $this->mUser->edit($params);
        } catch (ValidatorException $e) {
            throw new UserException($e->getErrors(), $e->getMessage(), $e->getCode(), $e);
        }
 
    }
    
    /**
     * Установка привилегий для ролей
     */
    public function setPrives()
    {
        $this->mPrives_to_Roles->delAll();            
        foreach ($this->request->post as $key => $value){
            $arr = explode(':', $key);
            $rules = ['id_priv' => $arr[0], 'id_role'=> $value];
            $this->mPrives_to_Roles->setPrivesToRoles ($rules);
        }   
    }
    
    /**
     * Получение данных из различных моделей для заполнения полей формы
     * @return array
     */
    public function getFields ()
    {
        return [
            'roles' => $this->mRole->getAll('id'),
            'privs_to_roles' => $this->mPrives_to_Roles->getAllPrivsAsKeyToRoleArr(),
            'privs' => $this->mPriv->getAll(),
        ];
    }
    
    /**
     * шифрование 
     * @param string $str
     * @return string
     */
    private function myCrypt($str)
    {
        return hash('sha256', $str . SAULT);
    }
    
    /**
     * Генерация уникального токена
     * @return string
     */    
    private function getToken()
    {
        $pattern = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
        $token = '';

        for ($i = 0; $i < 16; $i++) {
                $symbol = mt_rand(0, strlen($pattern) - 1);
                $token .= $pattern[$symbol];
        }

        return $token;
    }
        
}
