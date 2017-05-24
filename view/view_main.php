<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<link href="<?=BASE_PATH?>style/styles.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<title><?=$title?></title>
</head>
<body>
	<div id="wrapper">
		<header>
			<div class="header line">
				<div class="wrapper">
					<div class="logo"></div>
					<div class="slogan">
						<div class="title">Блог Константина</div>
						<div class="subtitle">Курсовая работа - PHP1</div>
					</div>
					
                                        <div class="current_user"><span>User <span class="fa fa-user fa-fw"></span></span> : <i> <?=$user?></i></div>
                                        <div id="user_menu">
                                            <div class="user_menu"><a href="<?=BASE_PATH?>login">
                                            <?php if ($login === true):?>
                                                    Выйти</a> 
                                            <?php else: ?>
                                                Вход </a><span>|</span><a href="<?=BASE_PATH?>reg"> Регистрация</a>  
                                            <?php endif;?> 
                                            </div>
                                        </div>     
                                </div>
                        </div>		
			<div class="menu line">
				<div class="wrapper">
					<nav>
						<div class="show_menu">Меню</div>
						<ul>
                                                    <a href="<?=BASE_PATH?>"><li><i class="fa fa-home fa-fw"></i> Главная</li></a>
                                                    <a href="<?=BASE_PATH?>posts"><li><i class="fa fa-file-text fa-fw"></i> Список статей <i class="fa fa-angle-down fa-fw"></i>
                                                        <ul>
                                                            <?php foreach($articles as $article): ?>
                                                            <a href="<?=BASE_PATH?>post/<?= $article['id_article']?>"><li><?= $article['title'] ?></li></a>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </li></a>
                                                    <a href="<?=BASE_PATH?>contacts"><li><i class="fa fa-phone fa-fw"></i>Контакты</li></a>
						</ul>					
					</nav>
                                </div>
			</div>		
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
	<script src="<?=BASE_PATH?>js/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="<?=BASE_PATH?>js/scripts.js" type="text/javascript"></script>
</body>
</html>