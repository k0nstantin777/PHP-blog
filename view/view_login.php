<span class="h3">Вход на сайт</span>
<form id="form" method="post" action = "">
    <label for="login">Логин</label><br> 
    <input type="text" name="login" id="login"/><br>
    <label for="password">Пароль</label><br> 
    <input type="password" name="password" id="password"/><br>
    <input type="checkbox" name="remember">Запомнить меня<br><br>
    <input type="submit" id="submit" value="Войти"/>
</form>
  <p id="error"><?= $msg; ?></p>

 