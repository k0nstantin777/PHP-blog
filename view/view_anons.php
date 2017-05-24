<aside class="left">
<div class="col fl-l">
    <span class="h3">Анонс статей</span>
    <div class="anons">
        <ul>		
            <? foreach($articles as $article): ?>
            <li><span><?= $article['title'] ?></span><br>
                <p><?= mb_substr($article['text'], 0, 50);?>
                <a href="<?=BASE_PATH?>post/<?= $article['id_article']?>">...далее</a></p>
            </li>
            <? endforeach; ?>
        </ul>
    </div>    
</div>
<div class="clear"></div>
</aside>
 

