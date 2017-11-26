<?php if ($login === true): ?>
    <span class="h3">Вы уже авторизованны! </span>
<?php else: ?>
<span class="h3">Вход в админку</span>
<form id="form" method="post" action = "">
    <label for="login">Логин</label><br> 
    <input type="text" name="login" id="login" value="<?= $post['login'] ?? '' ?>"
        <?php if (isset($errors['login'])): ?>    
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
    /><br>
        <?php if (isset ($errors['password'])): ?>   
           <p id="error"> <?=$errors['password']?></p>
        <?php endif; ?> 
    <input type="checkbox" name="remember"> Запомнить меня<br><br>
    <input type="submit" id="submit" value="Войти"/>
</form>
<p id="error"><?= $msg ?? ''; ?></p>

<?php endif; ?>
<p class="p"><a href="<?=BASE_PATH?>">Перейти на главную</a></p>
