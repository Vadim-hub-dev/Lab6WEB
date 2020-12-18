<!doctype html>
<html lang="ru">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./css/styleValid.css">
	<script src="https://use.fontawesome.com/af06815fdc.js"></script> 

    <title>Exchange currency</title>
  </head>
  
  
  <body>
  
<?php
	require_once "../db.php"; // подключаем файл для соединения с БД
	session_start();
	require_once "../phpscripts/autoAthCook.php";
?>
  
  <header>
    <nav class="navbar navbar-expand-md navbar-light bg-light">
		<a class="navbar-brand" href="intro.php">
			<img src="logo.png" style="width:54px;">
			ExchCurrency
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" 
		aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item active">
					<a class="nav-link" href="intro.php">Главная</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="kursval.php">Курсы валют</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="otdel.php">Отделения</a>
				</li>
				<li class="nav-item mr-3">
					<!-- Если авторизован выведет логин -->
					<?php if(isset($_SESSION['auth'])) : ?>
					<?php include_once '../phpscripts/oneFromDB.php'; 
					$login = getDB($_SESSION['auth'], $link);?>
					<a href="lichkab.php"><?php echo $login['login']; ?></a></br>

					<!-- Пользователь может нажать выйти для выхода из системы -->
					<a href="../logout.php"><center>Выйти</center></a>
					<?php else : ?>

					<!-- Если пользователь не авторизован выведем кнопку на авторизацию -->
					<a href="../login.php" class="btn btn-outline-secondary mr-3">Войти в аккаунт</a>
					<?php endif; ?>
				</li>
			</ul>
			<form class="form-inline">
				<input class="form-control" type="search" placeholder="Search" aria-label="Search">
				<button class="btn btn-outline-primary" type="submit">Search</button>
			</form>
		</div>
	</nav>
  </header>
	
	<main>
	<?php include_once '../phpscripts/headings.php'; ?>
	<div class ="container text-center">
		<a class = "text-center"><h2>Список книг, после прочтения которых вы будете разбираться в бирже валют</h2></a>
		<?php echo $text; ?>
	</div>
	
	
	<div class="container-fluid pt-5">
		<div class="row">
			<div class="col-3"></div>
			<div class="col-6 text-center"><H1>Почему выбирают нас?</H1></div>
			<div class="col-3"></div>
		</div>
	</div>
	<div class="container">
		<div class="row" style="height: 450px;">
			<div class="col text-center align-self-start" style="outline-style : solid; outline-width : 3px; outline-color : rgb(0, 173, 255);">
			<br></br>
			<img src="act.png" style="width:100px;">
			<br><h3>Актуально</h3></br>
			<p>Мы ежедневно собираем и обновляем информацию банков по всей России.</p>
			</div>
			<div class="col-1">
			</div>
			<div class="col text-center align-self-center" style="outline-style : solid; outline-width : 3px; outline-color : rgb(0, 173, 255);">
			<br></br>
			<img src="econom.png" style="width:100px;">
			<br><h3>Выгодно</h3></br>
			<p>Вы сможете найти выгодные предложения, что сэкономит не только ваши деньги, но и время.</p>
			</div>
			<div class="col-1">
			</div>
			<div class="col text-center align-self-end" style="outline-style : solid; outline-width : 3px; outline-color : rgb(0, 173, 255);">
			<br></br>
			<img src="nadezh.png" style="width:100px;">
			<br><h3>Безопасно</h3></br>
			<p>Все данные, которые вы оставляете на сайте, надежно защищены протоколом SSL.</p>
			</div>
		</div>
	</div>
	</main>
	<?php require_once "../footer.php"; ?>
  </body>
</html>