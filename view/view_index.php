<span class="h1">Главная страница</span>
<?php if (!empty($prives)): ?>
<p><?=$login?>, добро пожаловать на блог по основам PHP</p>
<p>Вы авторизованны и можете добавлять
    <?php if (in_array('delete_post', $prives)):?>
       и удалять
    <?php endif; ?>
    <?php if (in_array('edit_post', $prives)):?>    
        и редактировать
    <?php endif; ?>    
        статьи</p>
<?php else: ?>
<p>Добро пожаловать на блог по основам PHP</p>
<p>Данный блог содержит статьи по основам PHP. 
    Для добавления и редактирования статей
    <a href="<?=BASE_PATH?>login">авторизуйтесь</a>
    или 
    <a href="<?=BASE_PATH?>reg">пройдите регистрацию</a>
</p>
<?php endif; ?>
<img width="600" height="350" src="style/img/php.jpeg"> 
    

    
