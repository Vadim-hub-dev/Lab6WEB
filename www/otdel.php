<!doctype html>
<html lang="ru">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<script src="https://use.fontawesome.com/af06815fdc.js"></script>

	<title>Exchange currency</title>
</head>

<body>

	<?php
	require_once "../db.php"; // подключаем файл для соединения с БД
	session_start();
	require_once "../phpscripts/autoAthCook.php";
	require_once "../head.php";
	?>

	<main>

		<div class="container pb-3 pt-3">
			<div class="row">
				<div class="col-3"></div>
				<div class="col-6 text-center">
					<H1>Точки обслуживания в Волгограде</H1>
				</div>
				<div class="col-3"></div>
			</div>
			<div class="col text-center">
				<form method="POST">
					<select class="form-control" id="searchFrom" name="searchFrom">
						<option value="0">Поиск по списку отделений</option>
						<option value="1">Поиск по списку сотрудников</option>
					</select>
					<select class="form-control" id="searchOrder" name="searchOrder">
						<option value="0">По возрастанию даты образования/вступления в команду</option>
						<option value="1">По убыванию даты образования/вступления в команду</option>
					</select>
					<input class="form-control" type="text" name="inputSearch" id="inputSearch" placeholder="Поиск информации">
					<button class="btn btn-outline-primary" id="full" name="full">Вывести полный список</button>
					<button class="btn btn-outline-primary" id="search" name="search">Сделать поиск</button>
				</form>

			</div>

			<?php
			if (isset($_POST["full"])) :
				$result = mysqli_query($link, "SELECT * FROM `otdelenia`");
				$count = mysqli_num_rows($result);
				if ($count >= 1) :
					while ($row = mysqli_fetch_array($result)) { ?>
						<div class="row pt-3">
							<div class="col">
								<h4><?php echo $row['name'] ?>
									<?php if (isset($_SESSION["auth"])) : ?>
										<a href="../phpscripts/otdelDelete.php?delOtdel_id=<?php echo $row['id']; ?>" class="btn fa fa-trash" style="color:blue;"></a>
										<a href="../phpscripts/otdelCorrect.php?correctOtdel_id=<?php echo $row['id']; ?>" class="btn fa fa-cog" style="color:blue;"></a>
									<?php endif; ?>
								</h4>
								<p class="pt-0 text-center"> Адрес: <?php echo $row['address'] ?> </p>
								<p class="pt-0 text-center"> Контактный телефон: <?php echo $row['phone'] ?> </p>
							</div>
							<div class="col">
								<h6>Режим работы: </h6>
								<p class="pt-0 text-center"> <?php echo $row['rezhim_raboty'] ?> </p>
							</div>
						</div>
						<div class="row">
							<p><strong>Сотрудники отделения: </strong></p>
						</div>
						<?php
						$result_query = mysqli_query($link, "SELECT * FROM sotrudniki WHERE otdel_ID = '" . mysqli_real_escape_string($link, $row['id']) . "'");
						$count_query = mysqli_num_rows($result_query);
						$n = 1;
						if ($count_query >= 1) :
							while ($str = mysqli_fetch_array($result_query)) { ?>
								<div class="row mt-4">
									<div class="media ml-5">
										<img class="mr-3" width="20%" src="../img/sotrudImg/<?php echo $str['image'] ?>">
										<div class="media-body align-self-center">
											<strong> <?php echo $n . '.' . $str['name'] ?> </strong>
											<?php if (isset($_SESSION["auth"])) : ?>
												<a href="../phpscripts/sotrudDelete.php?del_id=<?php echo $str['idSot']; ?>" class="btn fa fa-trash" style="color:blue;"></a>
												<a href="../phpscripts/sotrudCorrect.php?correct_id=<?php echo $str['idSot']; ?>?" class="btn fa fa-cog" style="color:blue;"></a>
											<?php endif; ?>
											<p>Дата рождения: <?php echo $str['birhDate'] ?>
												<br>Дата вступления в нашу команду: <?php echo $str['rabDate'] ?>
												<br>Должность: <?php echo $str['dolzh'] ?></p>
										</div>
									</div>
								</div>
							<?php $n += 1;
							} ?>

						<?php
						else :
							echo 'Пока в этом отделении не зарегестрированы сотрудники.';
						endif;
						echo '<hr align="center" width="100%" color="Grey" />';
					}
				else :
					echo '<h2>Пока нет доступных отделений.</h2>';
				endif;
			elseif (isset($_POST["search"])) :
				if ($_POST["searchFrom"] == 1) {
					if ($_POST["searchOrder"] == 0) {
						$result_search = mysqli_query($link, 'SELECT * FROM `sotrudniki` WHERE MATCH(`name`, `dolzh`) AGAINST("' . $_POST["inputSearch"] . '") ORDER BY `rabDate` ASC');
						$count_search = mysqli_num_rows($result_search);
					} elseif ($_POST["searchOrder"] == 1) {
						$result_search = mysqli_query($link, 'SELECT * FROM `sotrudniki` WHERE MATCH(`name`, `dolzh`) AGAINST("' . $_POST["inputSearch"] . '") ORDER BY `rabDate` DESC');
						$count_search = mysqli_num_rows($result_search);
					}
					if ($count_search >= 1) :
						$n = 1;
						while ($str = mysqli_fetch_array($result_search)) { ?>
							<div class="row mt-4">
								<div class="media ml-5">
									<img class="mr-3" width="20%" src="../img/sotrudImg/<?php echo $str['image'] ?>">
									<div class="media-body align-self-center">
										<strong> <?php echo $n . '.' . $str['name'] ?> </strong>
										<?php if (isset($_SESSION["auth"])) : ?>
											<a href="../phpscripts/sotrudDelete.php?del_id=<?php echo $str['idSot']; ?>" class="btn fa fa-trash" style="color:blue;"></a>
											<a href="../phpscripts/sotrudCorrect.php?correct_id=<?php echo $str['idSot']; ?>?" class="btn fa fa-cog" style="color:blue;"></a>
										<?php endif; ?>
										<p>Дата рождения: <?php echo $str['birhDate'] ?>
											<br>Дата вступления в нашу команду: <?php echo $str['rabDate'] ?>
											<br>Должность: <?php echo $str['dolzh'] ?></p>
											<p class="pt-0 text-center"><a href="../phpscripts/infoFromOtdel.php?Sotrud_id=<?php echo $str['idSot']; ?>" style="color:blue;">Подробнее...</a></p>
									</div>
								</div>
							</div>
						<?php $n += 1;
						} ?>

						<?php
					else :
						echo '<h2><center>Сотрудники по поиску не найдены!</center></h2>';
					endif;
					echo '<hr align="center" width="100%" color="Grey" />';
				} elseif ($_POST["searchFrom"] == 0) {
					if ($_POST["searchOrder"] == 0) {
						$result_search = mysqli_query($link, 'SELECT * FROM `otdelenia` WHERE MATCH(`name`, `address`) AGAINST("' . $_POST["inputSearch"] . '") ORDER BY `date_obr` ASC');
						$count_search = mysqli_num_rows($result_search);
					} elseif ($_POST["searchOrder"] == 1) {
						$result_search = mysqli_query($link, 'SELECT * FROM `otdelenia` WHERE MATCH(`name`, `address`) AGAINST("' . $_POST["inputSearch"] . '") ORDER BY `date_obr` DESC');
						$count_search = mysqli_num_rows($result_search);
					}
					if ($count_search >= 1) :
						while ($row = mysqli_fetch_array($result_search)) { ?>
							<div class="row pt-3">
								<div class="col">
									<h4><?php echo $row['name'] ?>
										<?php if (isset($_SESSION["auth"])) : ?>
											<a href="../phpscripts/otdelDelete.php?delOtdel_id=<?php echo $row['id']; ?>" class="btn fa fa-trash" style="color:blue;"></a>
											<a href="../phpscripts/otdelCorrect.php?correctOtdel_id=<?php echo $row['id']; ?>" class="btn fa fa-cog" style="color:blue;"></a>
										<?php endif; ?>
									</h4>
									<p class="pt-0 text-center"> Адрес: <?php echo $row['address'] ?> </p>
									<p class="pt-0 text-center"> Контактный телефон: <?php echo $row['phone'] ?> </p>
								</div>
								<div class="col">
									<h6>Режим работы: </h6>
									<p class="pt-0 text-center"> <?php echo $row['rezhim_raboty'] ?> </p>
									<p class="pt-0 text-center"><a href="../phpscripts/infoFromOtdel.php?Otdel_id=<?php echo $row['id']; ?>" style="color:blue;">Подробнее...</a></p>
								</div>

							</div>
							<hr align="center" width="100%" color="Grey" />
			<?php }
					else :
						echo '<h2><center>Не найдено отделений соответсвующих поиску!</center></h2>';
					endif;
				}
			endif;
			?>
			<div class="row">
				<?php if (isset($_SESSION['auth'])) : ?>
					<div class="col text-center"><a href="../phpscripts/otdelNew.php" class="btn btn-outline-primary">Добавить отделение</a></div>
					<div class="col text-center"><a href="../phpscripts/sotrudNew.php" class="btn btn-outline-primary">Добавить сотрудника</a></div>
				<?php endif ?>
			</div>
		</div>
	</main>
	<?php require_once "../footer.php"; ?>

</body>

</html>