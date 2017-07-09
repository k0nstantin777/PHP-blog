<?php foreach ($msgs as $key => $err): 
      if ($key === 'login'){
          $err_login = $err; 
      } elseif ($key === 'password'){
          $err_pass = $err; 
      } else {
          $msg = $err;
      }   
      endforeach;
?>
<span class="h3">Для регистрации заполните форму</span>
<form id="form" method="post" action = "">
    <label for="login">Логин</label><br> 
    <input type="text" name="login" id="login"/>
    <p id="error"><?= $err_login ?? ''; ?></p>
    <label for="password">Пароль</label><br> 
    <input type="password" name="password" id="password"/>
    <p id="error"><?= $err_pass ?? ''; ?></p>
    <input id="submit" type="submit" value="Зарегестрироваться"/>
</form>
  <p id="error"><?= $msg ?? ''; ?></p>


   