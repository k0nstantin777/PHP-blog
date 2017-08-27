<span class="h1"><?= $user['login']?></span>
<article class="article">
    <p>User ID: <?= $user['id_user']?></p>
    <p>User Role: <?= $role['name']?></p>
    <p>Дата регистрации: <?= $user['dt']?></p>
    <a href="<?= $back ?>" title="Назад" class="option back"><i class="fa fa-arrow-left"></i> </a>
    <?php if (in_array('edit_user', $prives)):?>
        <a href="<?=BASE_PATH?>admin/user_edit/<?= $user['id_user'] ?>" title="Изменить" class="option edit" name = "<?= $user['login'] ?>"> <i class="fa fa-edit"></i> </a> 
    <?php endif;?>
    <?php if (in_array('delete_user', $prives)): ?>    
        <a href="<?=BASE_PATH?>admin/user_delete/<?= $user['id_user'] ?>" title="Удалить" class="option del" name = "<?= $user['login'] ?>" onclick="return confirm('Удалить пользователя \'<?= $user['title'] ?>\' ?');"> <i class="fa fa-times-circle-o"></i></a>
    <?php endif;?>
</article>   
