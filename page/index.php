<?php
    /*
     * Главная страница блога
     */
    
    $aside = template('view_anons', [
         'articles' => get_all_articles()
     ]);
    
    $inner = template('view_index', [

    ]);
    
    $title = 'Мой блог';