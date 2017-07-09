<?php foreach ($msgs as $key => $err): 
      if ($key === 'title'){
          $err_title = $err; 
      } elseif ($key === 'text'){
          $err_text = $err; 
      } else {
          $msg = $err;
      }   
      endforeach;
?>
<span class="h1">Изменить статью</span>
<p>Отредактируйте статью и нажмите - Изменить</p>
<form id="form" action="" method="post">
    <label for="title">Имя статьи</label><br> 
    <input type="text" name="title" value="<?= $name; ?>" id="title">
    <p id="error"> <?=$err_title ?? ''?></p> 
    <label for="content">Текст статьи</label><br>
    <textarea name="content" rows = "10" cols="50" id="content"><?= $text; ?></textarea>
    <p id="error"><?=$err_text ?? ''?></p>
    <input id="submit" type="submit" name = "submit" value="Изменить">
</form>
<a href="<?= explode('?', $back)[0] ?>" title="Назад" class="option back"><i class="fa fa-arrow-left"></i> </a>
<p id="error"> <?=$msg ?? ''?></p> 