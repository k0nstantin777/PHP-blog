<span class="h1">Управление пользователями</span>
<article>
<ul>
    <?php foreach($users as $user): ?>
    <li>
        <span class="title">
            <a href="<?=BASE_PATH?>admin/user/<?= $user['id_user'] ?>" id="name" title="Читать" name ="<?= $user['login'] ?>"><?= $user['login'] ?></a>
            <span class = "option"> ( <?= date('H:i d-m-Y', strtotime($user['dt'])) ?> ) </span>
        </span>
        <?php if (in_array('edit_user', $prives)):?>
            <a href="<?=BASE_PATH?>admin/user_edit/<?= $user['id_user'] ?>" class="option edit" title="Изменить" name = "<?= $user['login'] ?>"><i class="fa fa-edit"></i></a>
        <?php endif;?>
        <?php if (in_array('delete_user', $prives)): ?>
            <a href="<?=BASE_PATH?>admin/user_delete/<?= $user['id_user'] ?>" class="option del" title="Удалить" name = "<?= $user['login'] ?>" onclick="return confirm('Удалить пользователя: \'<?= $user['login'] ?>\' ?');"> <i class="fa fa-times-circle-o"></i></a>
        <?php endif;?>
    </li>
    <?php endforeach; ?> 
</ul>
    <?php if (in_array('add_user', $prives)): ?>
    <a class="add" href="<?=BASE_PATH?>admin/user_add">Добавить пользователя</a>
    <?php endif;?>
    <?php if (in_array('edit_prive', $prives)): ?>
    <a class="add" href="<?=BASE_PATH?>admin/privs">Настроить права</a>
    <?php endif;?>
</article> 
<p class="p"><i><?=$msg?></i></p>
   
