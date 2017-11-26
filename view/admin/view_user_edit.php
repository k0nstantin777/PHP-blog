<span class="h1">Изменить данные пользователя</span>

<form id="form" action="" method="post">
    <input type="hidden" name="id" value="<?= $id; ?>">
    <label for="title">Введите новое имя пользователя</label><br> 
    <input type="text" name="login" value="<?= $name; ?>"
        <?php if (isset ($errors['login'])): ?>    
           class="error"
        <?php endif; ?>   
    id="title"> <br>
        <?php if (isset ($errors['login'])): ?>   
           <p id="error"> <?=$errors['login']?></p>
        <?php endif; ?>     
    <label for="password">Введите новый пароль (либо оставьте пустым, если пароль не меняется)</label><br>
    <input type="hidden" name="password" value="<?=$password; ?>">
    <input type="password" name="new_password"  id="new_password"
        <?php if (isset ($errors['uncryptPass'])): ?>
            class="error"
        <?php endif; ?>      
            value="<?=$new_password; ?>"> <br>
        <?php if (isset ($errors['uncryptPass'])): ?>   
           <p id="error"> <?=$errors['uncryptPass']?></p>
        <?php endif; ?>
    <label for="role">Тип пользователя</label><br>
    <select name="role"  id="role">      
        <?php foreach ($roles as $one):?>
            <option value="<?=$one['id']?>"
            <?php if ($role['id'] == $one['id']): ?>
               selected="selected" 
            <?php endif;?>
            >   
            <?=$one['name']?></option>
         <?php endforeach; ?>
    </select>    
    <br>
    <input id="submit" type="submit" name = "submit" value="Изменить">
</form>
<a href="<?= $back ?>" title="Назад" class="option back"><i class="fa fa-arrow-left"></i> </a>
<p id="error"> <?=$msg ?? ''?></p> 