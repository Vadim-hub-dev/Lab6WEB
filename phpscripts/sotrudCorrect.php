<!DOCTYPE html>
<html lang="ru">

<head>
	<title>Форма добавления сотрудника</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/styleValid.css">
	<meta content="text/html; charset=utf-8">
</head>

<body>
	<?php
	require_once "../db.php"; // подключаем файл для соединения с БД
	session_start();
	define("UPLOAD_DIR", "C://xampp/htdocs/obmenpunkt/img/sotrudImg/");
	$sotrud = mysqli_query($link, "SELECT * FROM `sotrudniki` WHERE `idSot` = '" . mysqli_real_escape_string($link, $_GET["correct_id"]) . "'");
	$sotr = mysqli_fetch_array($sotrud);
	$count_sotr = mysqli_num_rows($sotrud);
	if (isset($_SESSION['auth'])) {
		$data = $_POST;

		if (isset($data['newSotrud_name'])) {

			// Создаем массив для сбора ошибок
			$errors = array();

			if (!$count_sotr) {
				$errors[] = 'Не найден сотрудник для редактирования!';
			}

			if (!empty(trim($data["newSotrud_name"]))) {
				if (!preg_match('/^[a-zA-Zа-яёА-ЯЁ\s]+$/iu', $data["newSotrud_name"])) {
					$errors[] = 'Неверно введены имя и фамилия сотрудника';
				}
			} else {
				$errors[] = 'Вы не отправили имя и фамилию сотрудника';
			}

			if (!empty(trim($data["newSotrud_dolzh"]))) {
				if (!preg_match('/^[a-zA-Zа-яёА-ЯЁ\s]+$/iu', $data["newSotrud_dolzh"])) {
					$errors[] = 'Неверно введена должность сотрудника';
				}
			} else {
				$errors[] = 'Вы не отправили должность сотрудника';
			}

			if (empty(trim($data["newSotrud_otdel"]))) {
				$errors[] = 'Вы не отправили отдел сотрудника';
			}

			if (isset($data["newSotrud_birthdate"])) {

				$date = Datetime::createFromFormat('Y-m-d', $data["newSotrud_birthdate"]);
				$date1 = new Datetime('-18 years');

				if (!$date) {
					$errors[] = 'Дата рождения сотрудника введена в неверном формате';
				} elseif ($date > $date1) {
					$errors[] = 'Сотруднику должно быть минимум 18 лет для работы в компании';
				}
			} else {
				$errors[] = 'Вы не отправили дату рождения сотрудника';
			}

			if (isset($data["newSotrud_rabdate"])) {

				$date = Datetime::createFromFormat('Y-m-d', $data["newSotrud_rabdate"]);

				if (!$date) {
					$errors[] = 'Дата начала работы сотрудника введена в неверном формате';
				}
			} else {
				$errors[] = 'Вы не отправили дату начала работы сотрудника';
			}

			if (!empty($_FILES["newSotrud_file"])) {

				$file = $_FILES["newSotrud_file"];

				if (!in_array($file["type"], ['image/bmp', 'image/gif', 'image/jpg', 'image/png'])) {
					$errors[] = 'Тип файла не разрешён!</p>';
				} else {
					$file["type"] = explode('/', $file["type"])[1];
				}

				if ($file["size"] > 8 * 1000 * 1000) {
					$errors[] = 'Файл слижком большой';
				}

				if ($file["error"] !== UPLOAD_ERR_OK) {
					$errors[] = 'Ошибка загрузки файла!';
				}

				if (!$errors) {

					do {

						$filename = md5(uniqid() . time()) . '.' . $file["type"];
						$folder = UPLOAD_DIR . $filename;
					} while (file_exists($folder));

					if (!move_uploaded_file($file["tmp_name"], $folder)) {
						$errors[] = 'Не удалось сохранить файл!';
					}
				}
			} else {
				$errors[] = 'Не выбрана фотография сотрудника!';
			}

			if (!$errors) {

				if ($count_sotr) {
					if (unlink("../img/sotrudImg/{$sotr['image']}")) {
						$result = mysqli_query(
							$link,
							"UPDATE `sotrudniki` SET 
                        `name` = '{$data["newSotrud_name"]}', 
                        `birhDate` = '{$data["newSotrud_birthdate"]}', 
                        `rabDate` = '{$data["newSotrud_rabdate"]}', 
                        `dolzh` = '{$data["newSotrud_dolzh"]}', 
                        `image` = '{$filename}',
                        `otdel_ID` = '{$data["newSotrud_otdel"]}'
                        WHERE `sotrudniki`.`idSot` = '" . mysqli_real_escape_string($link, $sotr["idSot"]) . "'"
						);
						echo '<div style="color: green; ">Сотрудник успешно добавлен! Можно вернуться на <a href="../www/intro.php">главную</a>.</div><hr>';
						header('Location: ../www/otdel.php');
					} else {
						echo 'Не удалось удалить файл с сервера!';
					}
				} else {
					echo '<div style="color: red;">Не удалось добавить сотрудника, возможно вы неверно ввели его отдел!</div>';
				}
			} else {
				// array_shift() извлекает первое значение массива array и возвращает его, сокращая размер array на один элемент. 
				echo '<div style="color: red; ">' . array_shift($errors) . '</div><hr>';
			}
		}
	} else {
		echo '<h1 style="color:red">Эта функция доступна только для зарегестрированных пользователей!</h1>';
	}
	$otdel = mysqli_query($link, "SELECT `id`, `name`, `address` FROM `otdelenia`");
	?>

	<div class="container-fluid pt-5">
		<div class="row align-items-center">
			<div class="col-sm-12">
				<div class="row justify-content-center">
					<div class="col-6 text-center">
						<div class="media">
							<img class="mr-3" width="20%" src="../img/sotrudImg/<?php echo $sotr['image'] ?>">
							<div class="media-body align-self-center">
								<strong> <?php echo $sotr['name'] ?> </strong>
								<?php if (isset($_SESSION["auth"])) : ?>
									<a href="./sotrudDelete.php?del_id=<?php echo $sotr['idSot']; ?>" class="btn fa fa-trash" style="color:blue;"></a>
								<?php endif; ?>
								<br>Дата рождения: <?php echo $sotr['birhDate'] ?>
								<br>Дата вступления в нашу команду: <?php echo $sotr['rabDate'] ?>
								<br>Должность: <?php echo $sotr['dolzh'] ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container mt-5">
			<h2>Форма изменения данных сотрудника</h2><br>
			<form id="newSotrudWithVal" method="post" enctype="multipart/form-data">
				<div class="form-list">
					<label>
						<h6>Введите новые данные сотрудника! </h6>
					</label>
					<div>
						<input class="form-control" type="text" id="newSotrud_name" name="newSotrud_name" maxlength="50" value="" placeholder="Введите имя и фамилию сотрудника">
						<span class="error"></span>
					</div>
					<div class="mt-3">
						<input class="form-control" type="text" id="newSotrud_dolzh" name="newSotrud_dolzh" maxlength="50" value="" placeholder="Введите должность сотрудника">
						<span class="error"></span>
					</div>
					<div class="mt-3">
					<label>Выберите отдел:</label>
					<select class="form-control" id="newSotrud_otdel" name="newSotrud_otdel">
						<?php
						while ($resultOtdel = mysqli_fetch_array($otdel)) {
							echo "<option value='{$resultOtdel['id']}'> {$resultOtdel['name']} по адресу {$resultOtdel['address']} </option>";
						}
						?>
					</select>
					<span class="error"></span>
				</div>
					<div class="mt-3">
						<label>Дата начала работы сотрудника в компании:</label>
						<input class="form-control" type="date" id="newSotrud_rabdate" name="newSotrud_rabdate">
						<span class="error"></span>
					</div>
					<div class="mt-3">
						<label>Дата рождения сотрудника:</label>
						<input class="form-control" type="date" id="newSotrud_birthdate" name="newSotrud_birthdate">
						<span class="error"></span>
					</div>
					<div class="mt-3">
						<label>Прикрепите фото сотрудника ( в формате jpeg/png/gif/bmp):</label>
						<input class="form-control" type="file" id="newSotrud_file" name="newSotrud_file">
						<span class="error"></span>
					</div>
					<div class="mt-3">
						<button id="do_incert" name="do_incert" class="btn btn-success" type="submit">Добавить сотрудника</button>
					</div>
				</div>
			</form>
			<br>
			<p>Вернуться в раздел <a href="../www/otdel.php">отделения</a>.</p>
		</div>
		<script src='../jsscripts/validSotrudCorrect.js?".time()."'></script>
</body>

</html>