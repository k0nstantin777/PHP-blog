<?php

/**
 * BaseController
 *
 * @author bumer
 */
class BaseController {
    protected $title;
    protected $content;
    protected $aside;
    protected $user = 'Гость';
    protected $login;
    protected $articles;


    public function __construct()
    {
        $this->title = '';
        $this->content = '';
        $mArticles = new PostModel(DB::get());
        $this->articles = $mArticles->getAll();
        $this->aside = $this->template('view_anons', ['articles' => $this->articles] );
        
        if(!Core::isAuth()){
            $this->login = false;
        } else {
            $this->login = true;
            $this->user = $_SESSION['login'];
        }
        
    }

    /* вывод шаблона страницы в браузере */
    public function response()
    {
        echo $html = $this->template('view_main', [
            'title' => $this->title,
            'content' => $this->content,
            'user' => $this->user,
            'login' => $this->login,
            'aside' => $this->aside,
            'articles' =>$this->articles
        ]);
    }
    
    /* подключение шаблона страницы*/ 
    protected function template($path, $vars = []){
        extract($vars);
        ob_start();
        include("view/$path.php");
        return ob_get_clean();
    }
    
    public function errorAction()
    {
        $this->title = 'Ошибка 404';
        $this->content = $this->template('404');
    }        
}
