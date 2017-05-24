<span class="h3">Добавить статью</span>
<p class="p">Для добавления статьи заполните форму и нажмите "Добавить"</p>
    <form id="form" action="" method="post">
        <label for="title">Имя статьи</label><br> 
        <input type="text" name="title" id="title" value="<?= $name; ?>" ><br>
        <label for="content">Текст статьи</label><br> 
        <textarea name="content" id = "content" rows = "10" cols="50"><?= $text; ?></textarea><br><br>
        <input type="submit" id="submit" value="Добавить">
    </form><br>
<a href="<?= explode('?', $back)[0] ?>" title="Назад" class="option back"><i class="fa fa-arrow-left"></i> </a>
<p id="error"><?= $msg; ?></p> 

 

