<!doctype html>
<html lang="ru">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script src="https://use.fontawesome.com/af06815fdc.js"></script>

	<title>Exchange currency</title>
</head>


<body>

	<?php
	require "../db.php"; // подключаем файл для соединения с БД
	session_start();
	require_once "../phpscripts/autoAthCook.php";
	require_once "../head.php";
	$languages = simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp");
	?>

	<main>

		<div class="container bg-light mt-5">
			<div class="row">
				<div class="col-6">
					<h4>Актуальный курс популярных валют по ЦБ</h4>
				</div>
				<div class="col-6 text-right">Дата обновления: <?php echo date("d/m/Y"); ?></div>
			</div>
			<hr align="center" width="90%" color="Grey" />
			<div class="row">
				<div class="col text-center">
					<h6>Название</h6>
				</div>
				<div class="col text-center">
					<h6>Сокращение</h6>
				</div>
				<div class="col text-center">
					<h6>Номинал</h6>
				</div>
				<div class="col text-center">
					<h6>Курс ЦБ</h6>
				</div>
			</div>
			<hr align="center" width="90%" color="Grey" />
			<?php
			foreach ($languages->Valute as $lang) {
				echo '<div class="row">';
				echo '<div class="col text-center">' . $lang->Name . '</div>';
				echo '<div class="col text-center">' . $lang->CharCode . '</div>';
				echo '<div class="col text-center">' . $lang->Nominal . '</div>';
				echo '<div class="col text-center"> = ' .  round(str_replace(',', '.', $lang->Value), 2) . ' руб.</div>';
				echo '</div>';
				echo '<hr align="center" width="90%" color="Grey" />';
			}
			?>
		</div>
	</main>
	<?php require_once "../footer.php"; ?>
</body>

</html>