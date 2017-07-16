<span class="h1">Изменить статью</span>
<p>Отредактируйте статью и нажмите - Изменить</p>
<form id="form" action="" method="post">
    <input type="hidden" name="id" value="<?= $id; ?>">
    <label for="title">Имя статьи</label><br> 
    <input type="text" name="title" value="<?= $name; ?>"
        <?php if (isset ($errors['title'])): ?>    
           class="error"
        <?php endif; ?>   
    id="title"> <br>
        <?php if (isset ($errors['title'])): ?>   
           <p id="error"> <?=$errors['title']?></p>
        <?php endif; ?>     
    <label for="content">Текст статьи</label><br>
    <textarea name="content" rows = "10" cols="50" id="content"
        <?php if (isset ($errors['text'])): ?>
            class="error"
        <?php endif; ?>      
            ><?=$text; ?></textarea> <br>
        <?php if (isset ($errors['text'])): ?>   
           <p id="error"> <?=$errors['text']?></p>
        <?php endif; ?> 
    <input id="submit" type="submit" name = "submit" value="Изменить">
</form>
<a href="<?= $back ?>" title="Назад" class="option back"><i class="fa fa-arrow-left"></i> </a>
<p id="error"> <?=$msg ?? ''?></p> 