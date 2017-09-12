<span class="h1">Список статей</span>
<article>
<ul>
    <?php foreach($articles as $article): ?>
    <li>
        <span class="title">
            <a href="<?=BASE_PATH?>post/<?= $article['id_article'] ?>" id="name" title="Читать" name ="<?= $article['title'] ?>"><?= $article['title'] ?></a>
            <span class = "option"> ( <?= date('H:i d-m-Y', strtotime($article['dt'])) ?> ) </span>
        </span>
        
    </li>
    <?php endforeach; ?> 
</ul>
    <?php if (in_array('add_post', $prives)): ?>
    <a class="add" href="<?=BASE_PATH?>add">Добавить статью</a>
    <?php endif;?>
</article>
<p class="p"><i><?=$msg?></i></p>
   
