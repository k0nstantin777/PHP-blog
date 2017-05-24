<span class="h3">Для регистрации заполните форму</span>
<form id="form" method="post" action = "">
    <label for="login">Логин</label><br> 
    <input type="text" name="login" id="login"/><br>
    <label for="password">Пароль</label><br> 
    <input type="password" name="password" id="password"/><br><br>
    <input id="submit" type="submit" value="Зарегестрироваться"/>
</form>
  <p id="error"><?= $msg; ?></p>


   