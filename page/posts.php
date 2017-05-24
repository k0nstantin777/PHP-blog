<?php
    /*
     * Страница выбора статьи
     */
    
    $articles = get_all_articles();
 
    /* вывод сообщения после выполнения действия */
    $msg = '';
    if (isset($_GET['success']) && !empty($_GET['success']) && $login === true){
        $success = $_GET['success'];
        $msgs = ['edit' => 'Изменения сохранены', 'add' => 'Статья добавлена', 'delete'=> 'Статья удалена'];
        
        $msg = ($msgs[$success]) ? $msgs[$success] : '';
        
    } 
    
    
    $aside = template('view_anons', [
         'articles' => $articles
     ]);
    
    $inner = template('view_posts', [
        'articles' => $articles,
        'login' => $login,
        'user' => $user,
        'msg'  => $msg,
    ]);
    
    $title = 'Статьи';