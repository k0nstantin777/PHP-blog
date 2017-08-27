<span class="h1">Управление статьями</span>
<article>
<ul>
    <?php foreach($articles as $article): ?>
    <li>
        <span class="title">
            <a href="<?=BASE_PATH?>admin/post/<?= $article['id_article'] ?>" id="name" title="Читать" name ="<?= $article['title'] ?>"><?= $article['title'] ?></a>
            <span class = "option"> ( <?= date('H:i d-m-Y', strtotime($article['dt'])) ?> ) </span>
        </span>
        <?php if (in_array('edit_post', $prives)):?>
            <a href="<?=BASE_PATH?>admin/post_edit/<?= $article['id_article'] ?>" class="option edit" title="Изменить" name = "<?= $article['title'] ?>"><i class="fa fa-edit"></i></a>
        <?php endif;?>
        <?php if (in_array('delete_post', $prives)): ?>
            <a href="<?=BASE_PATH?>admin/delete/<?= $article['id_article'] ?>" class="option del" title="Удалить" name = "<?= $article['title'] ?>" onclick="return confirm('Удалить статью: \'<?= $article['title'] ?>\' ?');"> <i class="fa fa-times-circle-o"></i></a>
        <?php endif;?>
    </li>
    <?php endforeach; ?> 
</ul>
    <?php if (in_array('add_post', $prives)): ?>
    <a class="add" href="<?=BASE_PATH?>admin/add">Добавить статью</a>
    <?php endif;?>
</article>
<p class="p"><i><?=$msg?></i></p>
   
