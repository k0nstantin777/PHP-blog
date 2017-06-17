<?php

/**
 * PostController
 *
 * @author bumer
 */

namespace controller;
use controller\BaseController,
    model\PostModel,
    core\Core,
    core\DB;

class PostController extends BaseController
{    
    private $mArticles;
    private $articles;
    
    public function __construct()
    {
        parent::__construct();
        $this->mArticles = new PostModel(DB::get());
        $this->articles = $this->mArticles->getAll();
        $this->menu = $this->template('view_menu', [
                'articles' => $this->articles,
                'login' => $this->login,    
        ]);
        
    }        

    /* главная страница */
    public function indexAction()
    {
        $this->title = 'Мой блог';
        $this->aside = $this->template('view_anons', ['articles' => $this->mArticles->getRandLimit(7)] );
        $this->content = $this->template('view_index');
    }

    /* страница вывода одной статьи /post */
    public function postAction($id) 
    {
        if ($article = $this->mArticles->getOne($id)){
            $this->content = $this->template('view_post', [
                'login' => $this->login,
                'article' => $article,
                'back' => $_SERVER['HTTP_REFERER'],
            ]);
            $this->title = $article['title'];
            $this->aside = '';    
        } else {
            $this->er404Action();
        }
    }

    /* страница вывода всех статей /posts */
    public function postsAction() 
    {
        /* вывод сообщения после выполнения действия */
        $msg = '';
        if (isset($_GET['success']) && !empty($_GET['success']) && $this->login === true){
            $success = $_GET['success'];
            $msgs = ['edit' => 'Изменения сохранены', 'add' => 'Статья добавлена', 'delete'=> 'Статья удалена'];

            $msg = ($msgs[$success]) ? $msgs[$success] : '';

        } 
        $this->content = $this->template('view_posts', [
            'articles' => $this->articles,
            'login' => $this->login,
            'user' => $this->user,
            'msg'  => $msg,
        ]);
        $this->aside = $this->template('view_anons', ['articles' => $this->mArticles->getRandLimit(7)] );
        $this->title = 'Статьи';
    }
    
    /* страница добавление статьи /add */
    public function addAction()
    {
        if(count($_POST) > 0){
            $name = trim(htmlspecialchars($_POST['title']));
            $text = trim(htmlspecialchars($_POST['content']));
            if($name == '' || $text == ''){
                $msg = 'Заполните все поля';
            } elseif (!Core::checkName($name, 'article')){ 
                $msg = 'Запрещенные символы в поле "Имя"'; 
            } else {
                $this->mArticles->add(['title'=>$name, 'content'=> $text]);     
                header("Location:".BASE_PATH. "posts?success=add");
                exit();
            } 
        } else {
            /* зашли на страницу методом GET */
            if(!$this->login){
                header("Location: ".BASE_PATH. "login");
                exit();
            }
            $name = '';
            $text = '';
            $msg = '';
        }

        $this->content = $this->template('view_add', [
            'name' => $name,
            'text' => $text,
            'back' => $_SERVER['HTTP_REFERER'],
            'msg'  => $msg
        ]);

        $this->title = 'Добавить статью';
    }
    
    /* страница редактрирование статьи /edit/id */
    public function editAction($id)
    {
        /* проверка отправки формы методом POST */
        if (count($_POST)>0){
            $msg = '';
            $name = trim(htmlspecialchars($_POST['title']));
            $text = trim(htmlspecialchars($_POST['content']));
            if(isset ($name) && isset ($text)){
                if(empty($name) || empty ($text)){
                    $msg = 'Заполните все поля';
                } elseif (!Core::checkName($name, 'article')){ 
                    $msg = 'Запрещенные символы в поле "Имя"'; 
                } else {
                    $this->mArticles->edit(['id'=>$params[1],'title'=>$name, 'content'=> $text]);  
                    header("Location:".BASE_PATH. "posts?success=edit");
                    exit();
                }
            } 

        } else {
        /* зашли на страницу методом GET */
            if(!$this->login){
                header("Location: ".BASE_PATH. "login");
                exit();
            }
            
            $msg = '';
            $article = $this->mArticles->getOne($id);
        }

        if ($article = $this->mArticles->getOne($id)){
            $name = $article['title'];
            $text = $article['text'];
            $this->content = $this->template('view_edit', [
                'name' => $name,
                'text' => $text,
                'back' => $_SERVER['HTTP_REFERER'],
                'msg'  => $msg,
            ]);
            $this->title = 'Изменить статью';        
        } else {
            $this->er404Action();
        }     
    }
    
    /* удаление статьи /delete/id */
    public function deleteAction()
    {
        if(!$this->login){
        header("Location: ".BASE_PATH. "login");
        exit();
        }
        //удалеяем статью
        if ($this->mArticles->delete($id)){
            header("Location: ".BASE_PATH. "posts?success=delete");
            exit();
        } else {
            $this->er404Action();
        }
    }         
}

