<?php
    /*
     * Контакты
     */
    
    $aside = template('view_anons', [
         'articles' => get_all_articles()
    ]);

    $inner = template('view_contact', [

    ]);
    
    $title = 'Контакты';