<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<link href="<?=BASE_PATH?>style/styles.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href="<?=BASE_PATH?>style/img/favicon.ico" rel="icon" >
	<title><?=$title?></title>
</head>
<body>
	<div id="wrapper">
		<header>
			<div class="header line">
				<div class="wrapper">
					<div class="logo"></div>
					<div class="slogan">
						<div class="title">Блог по основам PHP</div>
						<div class="subtitle">Основы языка программирования PHP</div>
					</div>
					
                                        <div class="current_user"><span>User <span class="fa fa-user fa-fw"></span></span> : <i> <?=$login?></i></div>
                                        <div id="user_menu">
                                            <div class="user_menu">
                                            <?php if (in_array('access_admin_console', $prives)):?>
                                                <a href="<?=BASE_PATH?>admin">Админка</a> <span>|</span>
                                            <?php endif;?>
                                            <?php if ($login !== 'Гость'):?>
                                                <a href="<?=BASE_PATH?>unlogin">Выйти</a> 
                                            <?php else: ?>
                                                <a href="<?=BASE_PATH?>login">Вход </a><span>|</span><a href="<?=BASE_PATH?>reg"> Регистрация</a>  
                                            <?php endif;?>
                                            
                                            </div>
                                        </div>     
                                </div>
                        </div>		
			
			<?=$menu?>
                        
		</header>
		<section>
			<div class="content line">
				<div class="wrapper">
<!--					<div class="stars">
						<div class="item"><div class="text">Лучшие<br> цены!</div></div>
						<div class="item"><div class="text">Качественный<br> товар!</div></div>
						<div class="item"><div class="text">Доставка в<br> день заказа!</div></div>
						<div class="item"><div class="text">Скидки<br> оптовикам!</div></div>
						<div class="clear"></div>
					</div>-->
					<?=$aside?>
					<section class="right">
						<?=$content?>
					</section>
					<div class="clear"></div>
				</div>
			</div>
		</section>
		<footer>
			<div class="footer line">
				<div class="wrapper">
					<span class="copy">&copy; Константин Н. Все права защищены!</span>
                                        <div class="phone">noskov.kos@gmail.com</div>
                                </div>
                               
			</div>
		</footer>
	</div>	
	<script src="<?=BASE_PATH?>style/js/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="<?=BASE_PATH?>style/js/scripts.js" type="text/javascript"></script>
</body>
</html>