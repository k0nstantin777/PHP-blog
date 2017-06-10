<?php
    /*
     * Контакты
     */
    
    $aside = $mArticles->template('view_anons', [
         'articles' => $articles
    ]);

    $inner = $mArticles->template('view_contact', [

    ]);
    
    $title = 'Контакты';