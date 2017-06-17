<span class="h1"><?= $article['title']?></span>
<article class="article">
    <p><?= nl2br($article['text']) ?></p>
    <a href="<?= explode('?', $back)[0] ?>" title="Назад" class="option back"><i class="fa fa-arrow-left"></i> </a>
    <?php if ($login === true): ?>
        <a href="<?=BASE_PATH?>edit/<?= $article['id_article'] ?>" title="Изменить" class="option edit" name = "<?= $article['title'] ?>"> <i class="fa fa-edit"></i> </a> 

        <a href="<?=BASE_PATH?>delete/<?= $article['id_article'] ?>" title="Удалить" class="option del" name = "<?= $article['title'] ?>" onclick="return confirm('Удалить статью \'<?= $article['title'] ?>\' ?');"> <i class="fa fa-times-circle-o"></i></a>

    <?php endif;?>
</article>   
