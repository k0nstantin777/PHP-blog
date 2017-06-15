<?php

/**
 * PostController
 *
 * @author bumer
 */
include_once __DIR__ . '/BaseController.php';
    
class PostController extends BaseController
{    
    
    private $mArticles;
    
    public function __construct()
    {
        parent::__construct();
        $this->mArticles = new PostModel(DB::get());
        $this->articles = $this->mArticles->getAll();
    }        


    public function indexAction()
    {
        $this->title = 'Мой блог';
        $this->aside = $this->template('view_anons', ['articles' => $this->articles] );
        $this->content = $this->template('view_index');
    }

    public function postAction()
    {
        
        $flag = true;
        $id = explode ('/', $_GET['q'])[1];
        $article = $this->mArticles->getOne($id);
        
        if ($article === false){
            $flag = false;
        } 

        if ($flag === true){
            $this->content = $this->template('view_post', [
                'login' => $this->login,
                'article' => $article,
                'back' => $_SERVER['HTTP_REFERER'],
            ]);
            $this->title = $article['title'];
            $this->aside = '';    
        } else {
            $this->title = 'Ошибка 404';
            $this->content = $this->template('404');
        }
        
        
    }

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
        $this->aside = $this->template('view_anons', ['articles' => $this->articles] );
        $this->title = 'Статьи';
    }
    
    
    public function addAction()
    {
        if(count($_POST) > 0){
            $mArticles = new PostModel(DB::get());

            $name = trim(htmlspecialchars($_POST['title']));
            $text = trim(htmlspecialchars($_POST['content']));
            if($name == '' || $text == ''){
                $msg = 'Заполните все поля';
            } elseif (!Core::checkName($name, 'article')){ 
                $msg = 'Запрещенные символы в поле "Имя"'; 
            } else {
                $mArticles->add(['title'=>$name, 'content'=> $text]);     
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
        $this->aside = '';    
           
    }
    
    public function editAction()
    {
        $flag = true;
        $mArticles = new PostModel(DB::get());
        
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
                    $mArticles->edit(['id'=>$params[1],'title'=>$name, 'content'=> $text]);  
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
            $id = explode ('/', $_GET['q'])[1];
            $article = $mArticles->getOne($id);
            if ($article === false) {
                $flag = false;
            } else {
                $name = $article['title'];
                $text = $article['text'];
            }
        }

        if ($flag === true){
            $this->content = $this->template('view_edit', [
                'name' => $name,
                'text' => $text,
                'back' => $_SERVER['HTTP_REFERER'],
                'msg'  => $msg,
            ]);
            $this->aside = '';
            $this->title = 'Изменить статью';        
        } else {
            $this->title = 'Ошибка 404';
            $this->content = $this->template('404');
        }     
    }
    
    public function deleteAction()
    {
        if(!$this->login){
        header("Location: ".BASE_PATH. "login");
        exit();
        }
        $mArticles = new PostModel(DB::get());
        $id = explode ('/', $_GET['q'])[1];
        //удалеяем статью
        if ($mArticles->delete($id)){
            header("Location: ".BASE_PATH. "posts?success=delete");
            exit();
        } else {
            $this->title = 'Ошибка 404';
            $this->content = $this->template('404');
            $this->aside = '';
        }
    }         
}

