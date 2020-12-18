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
	if (isset($_SESSION['auth'])) {
		$data = $_POST;

		if (isset($data['newSotrud_name'])) {
			// Создаем массив для сбора ошибок
			$errors = array();

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
					$errors[] = 'Файл слишком большой';
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

				$user = mysqli_query($link, "SELECT * FROM `otdelenia` WHERE `id` = '" . mysqli_real_escape_string($link, $data["newSotrud_otdel"]) . "' LIMIT 1");
				$row = mysqli_fetch_array($user);
				$count_row = mysqli_num_rows($user);

				if ($count_row) {
					$result = mysqli_query(
						$link,
						"INSERT INTO `sotrudniki` (`name`, `birhDate`, `rabDate`, `dolzh`, `image`, `otdel_ID`) VALUES
						(
							'{$data["newSotrud_name"]}',
							'{$data["newSotrud_birthdate"]}',
							'{$data["newSotrud_rabdate"]}',
							'{$data["newSotrud_dolzh"]}',
							'{$filename}',
							'{$row["id"]}'
						)"
					);
					echo '<div style="color: green; ">Сотрудник успешно добавлен! Можно вернуться на <a href="../www/intro.php">главную</a>.</div><hr>';
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

	<div class="container mt-5">
		<h2>Форма внесения нового сотрудника</h2><br>
		<form id="newSotrudWithVal" method="post" enctype="multipart/form-data">
			<div class="form-list">
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
	<script src='../jsscripts/valid_newSotrud.js?".time()."'></script>
</body>

</html>