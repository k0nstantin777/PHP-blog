<?php
/*
 * модель работы со статьями
 */
    include_once 'db.php';

    /* вывод всех статей */
    function get_all_articles () {
        $sql = "SELECT * FROM articles ORDER BY dt DESC";
        return db_query($sql)->fetchAll();
    }
    
    /* вывод одной статьи */
    function get_article ($id) {
        $sql = "SELECT * FROM articles WHERE id_article = :id";
        $query = db_query($sql, ['id' => $id]);
        return $query->fetch();
    }
    
    /* добавление статьи */
    function add_article ($title, $content){
        $sql = "INSERT INTO articles (title, text) VALUES (:title, :content)";
        db_query($sql, ['title' => $title, 'content' => $content]);
        return true;
    }
    
     /* редактирование статьи */
    function edit_article ($id, $title, $content){
        $sql = "UPDATE articles SET title = :title, text = :content WHERE id_article = :id";
        db_query($sql, ['id' => $id ,'title' => $title, 'content' => $content]);
        return true;
    }
    
     /* удаление статьи */
    function delete_article ($id){
        $sql = "DELETE FROM articles WHERE id_article = :id";
        db_query($sql, ['id' => $id]);
        return true;
    }
    
   

