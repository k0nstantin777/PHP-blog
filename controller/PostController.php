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
    core\DB,
    core\DBDriver,
    core\Validator,
    core\Request,
    core\exception\ValidatorException,
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

    /* главная страница */

    public function indexAction()
    {
        $this->title = 'Мой блог';
        $this->aside = $this->template('view_anons', ['articles' => $this->mArticles->getRandLimit(7)]);
        $this->content = $this->template('view_index');
    }

    /* страница вывода одной статьи /post */

    public function postAction()
    {
        $id = $this->request->get['id'];
        if ($article = $this->mArticles->getOne($id)) {
            $this->content = $this->template('view_post', [
                'login' => $this->login,
                'article' => $article,
                'back' => $this->request->getParam($this->request->server['HTTP_REFERER']),
            ]);
            $this->title = $article['title'];
            $this->aside = '';
        } else {
            throw new PageNotFoundException ();
        }
    }

    /* страница вывода всех статей /posts */

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

    /* страница добавление статьи /add */

    public function addAction()
    {
        if (!$this->login) {
                header("Location: " . BASE_PATH . "login");
                exit();
        }
                        
        if ($this->request->isPost()) {
            $name = $this->request->post['title'] ?? null;
            $text = $this->request->post['content'] ?? null;
                try {
                    $this->mArticles->add(['title' => $name, 'text' => $text, 'fields' => ['title', 'text']]);
                    header("Location:" . BASE_PATH . "posts?success=add");
                    exit();
                } catch (ValidatorException $e) {
                    $msgs = $e->getErrors();
                }
        } 
        
        $this->content = $this->template('view_add', [
            'name' => $this->request->post['title'] ?? '',
            'text' => $this->request->post['content'] ?? '',
            'back' => $this->request->server['HTTP_REFERER'] ?? BASE_PATH,
            'msgs' => $msgs ?? []
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
                
        /* проверка отправки формы методом POST */
        if ($this->request->isPost()) {
            $name = $this->request->post['title'] ?? null;
            $text = $this->request->post['content'] ?? null;
            
                try {    
                    $this->mArticles->edit(['id_article' => $id, 'title' => $name, 'text' => $text, 'fields' => ['id_article', 'title', 'text']]);
                    header("Location:" . BASE_PATH . "posts?success=edit");
                    exit();
                } catch (ValidatorException $e) {
                    $msgs = $e->getErrors();
                }
        } 
        
        if (!$article){
            throw new PageNotFoundException ();
        } else {
        
            $this->content = $this->template('view_edit', [
                    'name' => $this->request->post['title'] ?? $article['title'],
                    'text' => $this->request->post['content'] ?? $article['text'],
                    'back' => $this->request->server['HTTP_REFERER'] ?? BASE_PATH,
                    'msgs' => $msgs ?? []
            ]);
            $this->title = 'Изменить статью';
        }
    }

    /* удаление статьи /delete/id */

    public function deleteAction()
    {
        $id = $this->request->get['id'];
        if (!$this->login) {
            header("Location: " . BASE_PATH . "login");
            exit();
        }
        //удалеяем статью
        if ($this->mArticles->delete($id)) {
            header("Location: " . BASE_PATH . "posts?success=delete");
            exit();
        } else {
            throw new PageNotFoundException ();
        }
    }

}
