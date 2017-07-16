<?php if ($login === true): ?>
    <span class="h3">Вы уже авторизованы, для регистрации другого пользователя нажмите выход!</span>
    <p class="p"><a href="<?=BASE_PATH?>unlogin">Выход</a></p>
<?php elseif (isset ($get['success'])): ?>
    <span class="h3">Регистрация прошла успешно, теперь вы можете авторизоваться!</span>
    <p class="p"><a href="<?=BASE_PATH?>login">Авторизоваться</a></p>
<?php else: ?>
<span class="h3">Для регистрации заполните форму</span>
<form id="form" method="post" action = "">
    <label for="login">Логин</label><br> 
    <input type="text" name="login" id="login" value="<?=$post['login'] ?? ''?>"
        <?php if (isset ($errors['login'])): ?>    
           class="error"
        <?php endif; ?>
    /><br> 
        <?php if (isset ($errors['login'])): ?>   
           <p id="error"> <?=$errors['login']?></p>
        <?php endif; ?> 
    <label for="password">Пароль</label><br> 
    <input type="password" name="password" id="password" value="<?=$post['password'] ?? ''?>"
        <?php if (isset ($errors['password'])): ?>    
           class="error"
        <?php endif; ?>   
    /> <br>
        <?php if (isset ($errors['password'])): ?>   
           <p id="error"> <?=$errors['password']?></p>
        <?php endif; ?> 
    <input id="submit" type="submit" value="Зарегестрироваться"/>
</form>
<p id="error"><?= $msg ?? ''; ?></p>

<?php endif; ?>
<p class="p"><a href="<?=BASE_PATH?>">Перейти на главную</a></p>   