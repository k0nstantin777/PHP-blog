<?php

namespace core;

/**
 * Модуль работы с User 
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
use model\UserModel,
    model\SessionModel,
    core\exception\UserException,
    core\exception\ValidatorException;

class User
{

    private $mUser;
    
    private $mSession;
    
    public function __construct(UserModel $mUser, SessionModel $mSession)
    {
        $this->mUser = $mUser;
        $this->mSession = $mSession;
    }

    /**
     * Регистрация пользователя
     * @param array $fields
     * @return void
     * @throws UserException catched in UserController
     */
    public function registration(array $fields)
    {
        if (in_array(null, ArrayHelper::extract($fields, $this->mUser->getSchema()), true)){
            throw new UserException([], 'Не пытайтесь подделать форму!');
        }
        
        $user = $this->mUser->getByLogin($fields['login']);
        if (!empty($user)) {
            throw new UserException([], sprintf('Пользователь с логином %s уже существует!', $fields['login']));
        }

        //валидация применена в данном месте, для валидирования поле пароля в незашифрованном виде
        $this->mUser->validator->run($fields);
                
        if (!empty($this->mUser->validator->errors)) {
            throw new UserException($this->mUser->validator->errors);
        } 
        
        //после валидации пароль шифруем для дальнейшего добавления в БД
        $fields['password'] = $this->myCrypt($this->mUser->validator->clean['password']);

        try {
            return $this->mUser->addUser($fields);
        } catch (ValidatorException $e) {
            throw new UserException($e->getErrors(), $e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     * Авторизация
     * @param array $fields данные из формы
     */
    public function login (array $fields)
    {
        //проверка целостности формы
        if (in_array(null, ArrayHelper::extract($fields, $this->mUser->getSchema()), true)){
            throw new UserException([], 'Не пытайтесь подделать форму!');
        }
        
        //валидация полей формы
        $this->mUser->validator->run($fields);
                
        if (!empty($this->mUser->validator->errors)) {
            throw new UserException($this->mUser->validator->errors);
        }
        
        //проверка наличия пользователя
        $userLogin = $this->mUser->getByLogin($fields['login']);
        if (empty($userLogin)) {
            throw new UserException([], sprintf('Пользователя с логином %s не существует!', $fields['login']));
        }
        
        //проверка правильности введенного пароля
        if($fields['login'] == $userLogin['login'] && $this->myCrypt($fields['password']) == $userLogin['password']) {
            
            $this->mSession->add(['sid' => session_id(), 'user_id' => $userLogin[$this->mUser->id_name]]);

            //устанавливаем параметры сессии
            $this->mSession->setSessionParam('auth', true);
            $this->mSession->setSessionParam('login', $userLogin['login']);

            //устанавливаем cookie, если получен флаг remember
            if($fields['remember']){
                setcookie('login', $userLogin['login'], time() + 3600 * 24 * 365);
                setcookie('password', $userLogin['password'], time() + 3600 * 24 * 365);
            }
      
        } else {
            throw new UserException([], sprintf('Неправильный пароль!'));
        }   
    
    }
    
    /**
     * Определение авторизации пользователя
     * @param array $session 
     * @param array $cookie
     * @return boolean
     */    
    public static function auth (array $session = [], array $cookie = [])
    {
        $mSession = new SessionModel(new DBDriver(DB::get()), new Validator());
        
        //поиск записи текущей сессии в БД
        $user_session = $mSession->getSessionBySid(session_id());
        
        //поиск и удаление устаревших сессий (старше 30 минут) по user_id 
        $mSession->delOldSession($user_session['user_id']);
        
        //если нет $_SESSION['auth'] или нет записи текущей сессии в БД, пробуем получить cookie
        if(empty(ArrayHelper::get($session, 'auth')) || empty($user_session)){
            $login = ArrayHelper::get($cookie, 'login');
            $pass = ArrayHelper::get($cookie, 'password');
            $mUser = new UserModel(new DBDriver(DB::get()), new Validator());
            //определям есть ли cookie 
            if ($user = $mUser->getByLogin($login)){
                //проверяем корректность cookie
                if($login === $user['login'] && $pass === $user['password']){
                    //устанавливаем параметры сессии
                    $mSession->setSessionParam('auth', true);
                    $mSession->setSessionParam('login', $user['login']);
                    
                    //добавляем сессию в БД
                    $mSession->add(['sid' => session_id(), 'user_id' => $user[$mUser->id_name]]);
                                        
                    return true;
                }
                return false;
            }
            return false;
        }
        //если есть сессия в БД и есть $_SESSION['auth'] обновляем сессию 
        $mSession->edit(['id' => $user_session[$mSession->id_name], 'updated_at' => date("Y-m-d H:i:s", time())]);
        return true;
    }
    
    /**
     * Деавторизация
     * @param string $login текущий пользователь
     */
    public function unLogin ($login)
    {
        $id = $this->mSession->getIdSessionByLogin($login);
        $this->mSession->delete($id);
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
        
}
