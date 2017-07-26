<div class="menu line">
    <div class="wrapper">
        <nav>
            <div class="show_menu">Меню</div>
            <ul>
                <li><a href="<?=BASE_PATH?>"><i class="fa fa-home fa-fw"></i> Главная</a></li>
                 
                <li><a href="<?=BASE_PATH?>posts"><i class="fa fa-file-text fa-fw"></i> Список статей     
                    <?php if (!empty($prives)):?>
                        <i class="fa fa-angle-down fa-fw"></i></a>
                        <ul>
                            <?php foreach($articles as $article): ?>
                            <li><a href="<?=BASE_PATH?>post/<?= $article['id_article']?>"><?= $article['title'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;?>
                        </a>
                </li>
                <?php if (in_array('add_post', $prives)): ?> 
                <li><a href="<?=BASE_PATH?>add"><i class="fa fa-plus fa-fw"></i>Добавить статью</a></li>
                <?php endif;?>
                <li><a href="<?=BASE_PATH?>contacts"><i class="fa fa-phone fa-fw"></i>Контакты</a></li>
            </ul>					
        </nav>
    </div>
</div>		
