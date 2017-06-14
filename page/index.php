<?php
    /*
     * Главная страница блога
     */
     
    $aside = $mArticles->template('view_anons', [
         'articles' => $articles
     ]);
    
    $inner = $mArticles->template('view_index', [

    ]);
    
    $title = 'Мой блог';