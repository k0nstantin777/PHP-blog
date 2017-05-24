<span class="h1">Изменить статью</span>
<p>Отредактируйте статью и нажмите - Изменить</p>
<form id="form" action="" method="post">
    <label for="title">Имя статьи</label><br> 
    <input type="text" name="title" value="<?= $name; ?>" id="title"><br>
    <label for="content">Текст статьи</label><br>
    <textarea name="content" rows = "10" cols="40" id="content"><?= $text; ?></textarea><br><br>
    <input id="submit" type="submit" name = "submit" value="Изменить">
</form>
<a href="<?= explode('?', $back)[0] ?>" title="Назад" class="option back"><i class="fa fa-arrow-left"></i> </a>
<p id="error"><?= $msg; ?></p>