<!doctype html>
<html lang="ru">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script src="https://use.fontawesome.com/af06815fdc.js"></script> 
	

	</style>

    <title>Exchange currency</title>
  </head>
  
  
  <body>
  
	<?php require "../db.php"; // подключаем файл для соединения с БД 
	session_start();
	require_once "../phpscripts/autoAthCook.php";
	require_once "../head.php";
	?>
  
  <main>
	<div class="container">
		<?php if(isset($_SESSION['auth'])) : ?>
			<center><H1>Личный кабинет<H1></center>
			<H6>Добро пожаловать, 
			<?php include_once '../phpscripts/oneFromDB.php'; 
			$login = getDB($_SESSION['auth'], $link); 
			echo $login['name'];?>
			!</H6>
			<center><H3>Ваша история операций:<H3></center>
			<?php
			$result = mysqli_query($link, "SELECT * FROM lich WHERE login = '$login[login]'");
			if ($result)
			{?>
				<div class="row text-center">
					<div class="col">ID</div>
					<div class="col text-center">DATE</div>
					<div class="col">SUMMA</div>
					<div class="col">VALUTA</div>
					<div class="col">KURS</div>
					<div class="col">OPERACIA</div>
				</div>
				<hr align="center" width="100%" color="Grey"/>
			<?php
					while($res = mysqli_fetch_array($result))
					{
			?>
						<div class="row text-center">
							<div class="col"><?php echo $res['id']?></div>
							<div class="col"><?php echo $res['date']?></div>
							<div class="col"><?php echo $res['summa']?></div>
							<div class="col"><?php echo $res['valuta']?></div>
							<div class="col"><?php echo $res['kursval']?></div>
							<div class="col"><?php echo $res['operac']?></div>
						</div>
						<hr align="center" width="100%" color="Grey"/>
			<?php
					}
			}
			else
			{
				echo ' История операций пуста!';
			}
			?>
		<?php else : ?>
			<H1>Вы не авторизованы!</H1>
			<H6>Войдите в аккаунт и повторите попытку снова.</H6>
		<?php endif; ?>
	</div>
  </main>
  <?php require_once "../footer.php"; ?>
  </body>
</html>