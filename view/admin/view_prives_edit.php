<span class="h3">Настройка прав</span>
    <form id="form" method="post" action = "">
        <table cols="4">
            <caption>Таблица прав доступа</caption>
            <tr>
                <th>Пользователи</th>
                <?php foreach ($roles as $role):?>
                    <th><?= $role['name']?></th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <td>Права</td>
                <td colspan="3"></td>
            </tr>
                <?php foreach ($privs as $priv):?>
                    <tr>
                        <td><?= $priv['discription']?></td>
                        <?php foreach ($roles as $role):?>
                            <td><input type="checkbox" name="<?=$priv['id']?>:<?=$role['id']?>" value="<?=$role['id']?>"
                                <?php if (isset ($privs_to_roles[$priv['id']]) && in_array($role['id'] , $privs_to_roles[$priv['id']])):?>       
                                       checked="checked" 
                                <?php endif; ?> 
                                ></td>
                        <?php endforeach; ?>
                        
                    </tr>    
                <?php endforeach; ?>

        </table>
        
        
        
        <input id="submit" type="submit" value="Сохранить"/>
    </form>



<p class="p"><a href="<?=$back?>">Назад</a></p>   