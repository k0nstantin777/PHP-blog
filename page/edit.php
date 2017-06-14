<?php
/*
 * Редактирование статьи
 */
    
    /*аутентификация*/
    if(!$mUsers->isAuth()){
        header("Location:".BASE_PATH. "login");
        exit();
    } else {
        $login = true;
    }  
    
    $flag = true;
    
    /* проверка отправки формы методом POST */
    if (count($_POST)>0){
        $msg = '';
        $name = trim(htmlspecialchars($_POST['title']));
        $text = trim(htmlspecialchars($_POST['content']));
        if(isset ($name) && isset ($text)){
            if(empty($name) || empty ($text)){
                $msg = 'Заполните все поля';
            } elseif (!$mArticles->checkName($name, 'article')){ 
                $msg = 'Запрещенные символы в поле "Имя"'; 
            } else {
                $mArticles->editArticle(['id'=>$params[1],'title'=>$name, 'content'=> $text]);  
                header("Location:".BASE_PATH. "posts?success=edit");
                exit();
            }
        } 

    } else {
    /* зашли на страницу методом GET */
        $msg = '';
        $article = $mArticles->getOne($params[1]);
        if ($article === false) {
            $flag = false;
        } else {
            $name = $article['title'];
            $text = $article['text'];
        }
    }

    if ($flag === true){
        $inner = $mArticles->template('view_edit', [
            'name' => $name,
            'text' => $text,
            'back' => $_SERVER['HTTP_REFERER'],
            'msg'  => $msg
        ]);
        $title = 'Изменить статью';        
    } else {
        $title = 'Ошибка 404';
        $inner = $mArticles->template('404');
    }     