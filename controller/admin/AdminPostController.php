<?php

namespace controller\admin;

use core\exception\ValidatorException,
    core\exception\AccessException,
    core\exception\PageNotFoundException,
    core\Request,
    core\helper\ArrayHelper,
    core\ServiceContainer;

/**
 * Description of AdminPostController
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class AdminPostController extends AdminController
{
    public $mArticles;
   
    public function __construct(Request $request, ServiceContainer $container)
    {
        parent::__construct($request, $container);
                        
        $this->mArticles = $this->container->get('model.post');
        $this->menu = $this->template('view_menu', [
            'articles' => $this->mArticles->getAll(),
            'prives' => $this->user_prives,
        ]);
        

    }
    
    public function indexAction()
    {
        $this->priv_name = 'access_admin_console';
        if (!$this->user->can($this->priv_name)) {
            header("Location:". BASE_PATH . 'admin/login');
        }
        $this->title = 'Мой блог.Админка';
        $this->content = $this->template('admin/view_index', ['login' => $this->login, 'prives' => $this->user_prives]);
    }
    /* страница вывода одной статьи /post */

    public function postAction()
    {
        $this->priv_name = 'access_admin_console';
        if (!$this->user->can($this->priv_name)) {
            header("Location:". BASE_PATH . 'admin/login');
        }
        
        $id = $this->request->get['id'];
        $article = $this->mArticles->getOne($id);
        if (!$article){
            throw new PageNotFoundException ();
        }     
        $this->content = $this->template('admin/view_post', [
                'article' => $article,
                'back' => ArrayHelper::get($this->request->server, 'HTTP_REFERER', BASE_PATH),
                'prives' => $this->user_prives,
        ]);
        $this->title = $article['title'];
        
    }

    /**
     *  страница вывода всех статей /posts 
     */
    public function postsAction()
    {
        $this->priv_name = 'access_admin_console';
        if (!$this->user->can($this->priv_name)) {
            header("Location:". BASE_PATH . 'admin/login');
        }
        
        /* вывод сообщения после выполнения действия */
        $msg = '';
       
        if ($this->login !== 'Гость') {
            $success = isset($this->request->get['success']) ? $this->request->get['success'] : '';
            $msgs = ['edit' => 'Изменения сохранены', 'add' => 'Статья добавлена', 'delete' => 'Статья удалена'];
            $msg = isset($msgs[$success]) ? $msgs[$success] : '';
        }
        $this->content = $this->template('admin/view_posts', [
            'articles' => $this->mArticles->getAll(),
            'prives' => $this->user_prives,
            'msg' => $msg,
        ]);
        
        $this->title = 'Управление статьями';
    }
    
    /* страница редактрирование статьи /edit/id */
    public function post_editAction()
    {
        $this->priv_name = 'edit_post';

        if (!$this->user->can($this->priv_name)) {
            throw new AccessException ();
        }
        
        $id = $this->request->get['id'];
               
        $article = $this->mArticles->getOne($id);
        if (!$article){
            throw new PageNotFoundException ();
        } 
        
        $errors = [];
        $msg = '';
          
        // обработка формы методом POST 
        if ($this->request->isPost()) {
            //хэшируем поля формы
            $hash_form = md5('id'.'title'.'content'.'submit'.$id);
            try {    
                if ($hash_form !== md5(implode(array_keys($this->request->post)). $this->request->post['id'] )){
                    throw new FormException('Не пытайтесь подделать форму!');
                }
                $id = $this->request->post['id'];
                $name = $this->request->post['title'];
                $text = $this->request->post['content'];
                $this->mArticles->edit(['id_article' => $id, 'title' => $name, 'text' => $text]);
                header("Location:" . BASE_PATH . "admin/posts?success=edit");
                exit();
            } catch (ValidatorException $e) {
                $errors = $e->getErrors();
            } catch (FormException $e) {
                $msg = $e->getMessage();
            } 
        } 

        
        $this->content = $this->template('admin/view_post_edit', [
                'name' => ArrayHelper::get($this->request->post, 'title', $article['title']),
                'text' => ArrayHelper::get($this->request->post, 'content', $article['text']),
                'back' => ArrayHelper::get($this->request->server, 'HTTP_REFERER', BASE_PATH),
                'errors'=> $errors,
                'msg' => $msg,
                'prives' => $this->request->session->get('prives'),
                'id' => $id
                
        ]);
        $this->title = 'Изменить статью';
        
    }
    
    /**
     *  страница добавление статьи /add 
     */
    public function addAction()
    {
        $this->priv_name = 'add_post';
        
        if (!$this->user->can($this->priv_name)) {
            throw new AccessException ();
        }
        $errors = [];
        $msg = '';
        
        //хэш полей формы
        $hash_form = md5('title'.'content'.'submit'); 
        if ($this->request->isPost()) {
            
            try {
                if ($hash_form !== md5(implode(array_keys($this->request->post)))){
                    throw new FormException('Не пытайтесь подделать форму!');
                }
                $name = $this->request->post['title'];
                $text = $this->request->post['content'];
                $this->mArticles->add(['title' => $name, 'text' => $text]);
                header("Location:" . BASE_PATH . "admin/posts?success=add");
                exit();
            } catch (ValidatorException $e) {
                $errors = $e->getErrors();
            } catch (FormException $e) {
                $msg = $e->getMessage();
            } 
        } 
        
        $this->content = $this->template('admin/view_post_add', [
            'name' => ArrayHelper::get($this->request->post, 'title'),
            'text' => ArrayHelper::get($this->request->post, 'content'),
            'back' => ArrayHelper::get($this->request->server, 'HTTP_REFERER', BASE_PATH),
            'errors' => $errors,
            'msg' => $msg
        ]);

        $this->title = 'Добавить статью';
    }

    /**
     * удаление статьи /delete/id 
     */

    public function deleteAction()
    {
        $this->priv_name = 'delete_post';
        
        if (!$this->user->can($this->priv_name)) {
            throw new AccessException ();
        }
        
        $id = $this->request->get['id'];
       
        //удалеяем статью
        if (!$this->mArticles->delete($id)) {
            throw new PageNotFoundException ();
        }
        header("Location: " . BASE_PATH . "admin/posts?success=delete");
        exit();
    }
    
}
