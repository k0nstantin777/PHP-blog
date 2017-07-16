<span class="h3">Добавить статью</span>
<p class="p">Для добавления статьи заполните форму и нажмите "Добавить"</p>
    <form id="form" action="" method="post">
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
        
        <input type="submit" id="submit" value="Добавить">
    </form><br>
<a href="<?= explode('?', $back)[0] ?>" title="Назад" class="option back"><i class="fa fa-arrow-left"></i> </a>
<p id="error"> <?=$msg ?? ''?></p> 

 

