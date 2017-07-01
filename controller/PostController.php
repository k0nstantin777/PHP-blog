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
    core\Request;

class PostController extends BaseController
{

    private $mArticles;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->mArticles = new PostModel(new DBDriver(DB::get()));
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
            $this->er404Action('Ooooops... Something went wrong!');
        }
    }

    /* страница вывода всех статей /posts */

    public function postsAction()
    {
        /* вывод сообщения после выполнения действия */
        $msg = '';

        if ($this->login === true) {
            $success = isset($this->request->get['success']) ? $this->request->getParam($this->request->get['success']) : '';
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
        if ($this->request->isPost()) {
            $name = $this->request->getParam($this->request->post['title']);
            $text = $this->request->getParam($this->request->post['content']);
            if ($name == '' || $text == '') {
                $msg = 'Заполните все поля';
            } elseif (!Core::checkName($name, 'article')) {
                $msg = 'Запрещенные символы в поле "Имя"';
            } else {
                $this->mArticles->add(['title' => $name, 'text' => $text]);
                header("Location:" . BASE_PATH . "posts?success=add");
                exit();
            }
        } else {
            /* зашли на страницу методом GET */
            if (!$this->login) {
                header("Location: " . BASE_PATH . "login");
                exit();
            }
            $name = '';
            $text = '';
            $msg = '';
        }

        $this->content = $this->template('view_add', [
            'name' => $name,
            'text' => $text,
            'back' => $this->request->getParam($this->request->server['HTTP_REFERER']),
            'msg' => $msg
        ]);

        $this->title = 'Добавить статью';
    }

    /* страница редактрирование статьи /edit/id */

    public function editAction()
    {
        $id = $this->request->get['id'];
        /* проверка отправки формы методом POST */
        if ($this->request->isPost()) {
            $msg = '';
            $name = $this->request->getParam($this->request->post['title']);
            $text = $this->request->getParam($this->request->post['content']);
            if (isset($name) && isset($text)) {
                if (empty($name) || empty($text)) {
                    $msg = 'Заполните все поля';
                } elseif (!Core::checkName($name, 'article')) {
                    $msg = 'Запрещенные символы в поле "Имя"';
                } else {
                    $this->mArticles->edit(['id_article' => $id, 'title' => $name, 'text' => $text]);
                    header("Location:" . BASE_PATH . "posts?success=edit");
                    exit();
                }
            }
        } else {
            /* зашли на страницу методом GET */
            if (!$this->login) {
                header("Location: " . BASE_PATH . "login");
                exit();
            }
            $msg = '';
            $article = $this->mArticles->getOne($id);
        }

        if ($article = $this->mArticles->getOne($id)) {
            $name = $article['title'];
            $text = $article['text'];
            $this->content = $this->template('view_edit', [
                'name' => $name,
                'text' => $text,
                'back' => $this->request->getParam($this->request->server['HTTP_REFERER']),
                'msg' => $msg,
            ]);
            $this->title = 'Изменить статью';
        } else {
            $this->er404Action('Ooooops... Something went wrong!');
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
            $this->er404Action();
        }
    }

}
