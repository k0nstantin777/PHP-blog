<?php

/**
 * PostController
 *
 * @author bumer
 */

namespace controller;

use controller\BaseController,
    model\PostModel,
    core\DB,
    core\DBDriver,
    core\Validator,
    core\Request,
    core\ArrayHelper,
    core\exception\ValidatorException,
    core\exception\PostException,
    core\exception\PageNotFoundException;

class PostController extends BaseController
{

    public $mArticles;
    
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->mArticles = new PostModel(new DBDriver(DB::get()), new Validator());
        $this->menu = $this->template('view_menu', [
            'articles' => $this->mArticles->getAll(),
            'login' => $this->login,
        ]);
    }

    /**
     *  главная страница 
     */
    public function indexAction()
    {
        $this->title = 'Мой блог';
        $this->aside = $this->template('view_anons', ['articles' => $this->mArticles->getRandLimit(7)]);
        $this->content = $this->template('view_index', ['login' => $this->login,
                                                        'user' => $this->user]);
    }

    /* страница вывода одной статьи /post */

    public function postAction()
    {
        $id = $this->request->get['id'];
        $article = $this->mArticles->getOne($id);
        if (!$article){
            throw new PageNotFoundException ();
        }     
        $this->content = $this->template('view_post', [
                'login' => $this->login,
                'article' => $article,
                'back' => $this->request->server['HTTP_REFERER'],
        ]);
        $this->title = $article['title'];
        $this->aside = '';
    }

    /**
     *  страница вывода всех статей /posts 
     */
    public function postsAction()
    {
        /* вывод сообщения после выполнения действия */
        $msg = '';

        if ($this->login === true) {
            $success = isset($this->request->get['success']) ? $this->request->get['success'] : '';
            $msgs = ['edit' => 'Изменения сохранены', 'add' => 'Статья добавлена', 'delete' => 'Статья удалена'];
            $msg = isset($msgs[$success]) ? $msgs[$success] : '';
        }
        $this->content = $this->template('view_posts', [
            'articles' => $this->mArticles->getAll(),
            'login' => $this->login,
            'user' => $this->user,
            'msg' => $msg,
        ]);
        $this->aside = $this->template('view_anons', ['articles' => $this->mArticles->getRandLimit(7)]);
        $this->title = 'Статьи';
    }

    /**
     *  страница добавление статьи /add 
     */
    public function addAction()
    {
        if (!$this->login) {
            header("Location: " . BASE_PATH . "login");
            exit();
        }
        $errors = [];
        $msg = '';
        
        //устанавливаем схему ожидаемых полей из формы
        $this->mArticles->setSchema(['title', 'content']);
                
        if ($this->request->isPost()) {
            $fields = ArrayHelper::extract($this->request->post, $this->mArticles->getSchema());
                        
                try {
                    if (in_array(null, $fields, true)){
                        throw new PostException('Не пытайтесь подделать форму!');
                    } 
                    $name = $fields['title'];
                    $text = $fields['content'];
                    $this->mArticles->add(['title' => $name, 'text' => $text]);
                    header("Location:" . BASE_PATH . "posts?success=add");
                    exit();
                } catch (ValidatorException $e) {
                    $errors = $e->getErrors();
                } catch (PostException $e) {
                    $msg = $e->getMessage();
                } 
        } 
        
        $this->content = $this->template('view_add', [
            'name' => ArrayHelper::get($this->request->post, 'title'),
            'text' => ArrayHelper::get($this->request->post, 'content'),
            'back' => ArrayHelper::get($this->request->server, 'HTTP_REFERER', BASE_PATH),
            'errors' => $errors,
            'msg' => $msg
        ]);

        $this->title = 'Добавить статью';
    }

    /* страница редактрирование статьи /edit/id */
    public function editAction()
    {
        if (!$this->login) {
            header("Location: " . BASE_PATH . "login");
            exit();
        }
        
        $id = $this->request->get['id'];
               
        $article = $this->mArticles->getOne($id);
        if (!$article){
            throw new PageNotFoundException ();
        } 
        
        $errors = [];
        $msg = '';
        $this->mArticles->setSchema(['title', 'content', 'id']);
                
        /* проверка отправки формы методом POST */
        if ($this->request->isPost()) {
            $fields = ArrayHelper::extract($this->request->post, $this->mArticles->getSchema());
                
                try {    
                    if (in_array(null, $fields, true)){
                        throw new PostException('Не пытайтесь подделать форму!');
                    } 
                    $name = $fields['title'];
                    $text = $fields['content'];
                    $this->mArticles->edit(['id_article' => $id, 'title' => $name, 'text' => $text]);
                    header("Location:" . BASE_PATH . "posts?success=edit");
                    exit();
                } catch (ValidatorException $e) {
                    $errors = $e->getErrors();
                } catch (PostException $e) {
                    $msg = $e->getMessage();
                } 
        } 

        $this->content = $this->template('view_edit', [
                'name' => ArrayHelper::get($this->request->post, 'title', $article['title']),
                'text' => ArrayHelper::get($this->request->post, 'content', $article['text']),
                'back' => ArrayHelper::get($this->request->server, 'HTTP_REFERER', BASE_PATH),
                'errors'=> $errors,
                'msg' => $msg,
                'id' => $id
                
        ]);
        $this->title = 'Изменить статью';
        
    }

    /**
     * удаление статьи /delete/id 
     */

    public function deleteAction()
    {
        if (!$this->login) {
            header("Location: " . BASE_PATH . "login");
            exit();
        }
        
        $id = $this->request->get['id'];
       
        //удалеяем статью
        if (!$this->mArticles->delete($id)) {
            throw new PageNotFoundException ();
        }
        header("Location: " . BASE_PATH . "posts?success=delete");
        exit();
    }

}
