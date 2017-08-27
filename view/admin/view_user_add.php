<span class="h3">Добавление пользователя</span>
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
            <?php if (isset ($errors['uncryptPass'])): ?>    
               class="error"
            <?php endif; ?>   
        /> <br>
            <?php if (isset ($errors['uncryptPass'])): ?>   
               <p id="error"> <?=$errors['uncryptPass']?></p>
            <?php endif; ?> 

        <label for="role">Тип пользователя</label><br>
        <select name="role"  id="role">      
            <?php foreach ($roles as $role):?>
                <option value="<?=$role['id']?>">   
                <?=$role['name']?></option>
             <?php endforeach; ?>
        </select><br>  
        <input id="submit" type="submit" name="submit" value="Зарегестрировать"/>
    </form>
<p id="error"><?= $msg ?? ''; ?></p>


<p class="p"><a href="<?=$back?>">Назад</a></p>   