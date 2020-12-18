<!DOCTYPE html>
<html lang="ru">

<head>
	<title>Форма регистрации</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/styleValid.css">
	<meta content="text/html; charset=utf-8">
</head>

<body>

	<?php
	require_once "db.php"; // подключаем файл для соединения с БД
	session_start();

	// Создаем переменную для сбора данных от пользователя по методу POST
	$data = $_POST;

	if (isset($data['loginReg'])) {

		// Создаем массив для сбора ошибок
		$errors = array();

		// Проводим проверки
		if (!empty(trim($data["loginReg"]))) {
			$logins = mysqli_query($link, "SELECT * FROM `users` WHERE `login` = '" . mysqli_real_escape_string($link, $data["loginReg"]) . "'");
			$count_login = mysqli_num_rows($logins);

			if (!preg_match('/^[a-zA-Z0-9._\-]+$/iu', $data["loginReg"])) {
				$errors[] = 'Не корректно введён логин!';
			}
			if (mb_strlen($data['loginReg']) < 5 || mb_strlen($data['loginReg']) > 50) {
				$errors[] = "Недопустимая длина логина";
			}
			if ($count_login != 0) {
				$errors[] = "Пользователь с таким логином уже существует";
			}
		} else {
			$errors[] = 'Вы не отправили логин';
		}

		if (!empty(trim($data["emailReg"]))) {
			$emails = mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '" . mysqli_real_escape_string($link, $data["emailReg"]) . "'");
			$count_email = mysqli_num_rows($emails);

			if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $data['emailReg'])) {
				$errors[] = 'Не корректно введён email!';
			}
			if (mb_strlen($data['emailReg']) < 5 || mb_strlen($data['emailReg']) > 50) {
				$errors[] = "Недопустимая длина email";
			}
			if ($count_email != 0) {
				$errors[] = "Пользователь с таким email уже существует";
			}
		} else {
			$errors[] = 'Вы не отправили Email';
		}

		if (!empty(trim($data["nameReg"]))) {
			if (!preg_match('/^[a-zA-Zа-яА-ЯёЁ]+$/iu', $data["nameReg"])) {
				$errors[] = 'Не корректно введено имя!';
			}
			if (mb_strlen($data['nameReg']) < 2 || mb_strlen($data['nameReg']) > 30) {
				$errors[] = "Недопустимая длина имени";
			}
		} else {
			$errors[] = 'Вы не отправили имя';
		}

		if (!empty(trim($data["familyReg"]))) {
			if (!preg_match('/^[a-zA-Zа-яА-ЯёЁ]+$/iu', $data["familyReg"])) {
				$errors[] = 'Не корректно введено имя!';
			}
			if (mb_strlen($data['familyReg']) < 2 || mb_strlen($data['familyReg']) > 30) {
				$errors[] = "Недопустимая длина имени";
			}
		} else {
			$errors[] = 'Вы не отправили имя';
		}

		if (!empty(trim($data["passwordReg"]))) {
			if (!preg_match('/^[0-9a-zA-Z._\-]+$/iu', $data["passwordReg"])) {
				$errors[] = 'Не корректно введен пароль (можно использовать только латиницу, цифры и символы ".", "_", "-"!';
			}
			if (mb_strlen($data['passwordReg']) < 6 || mb_strlen($data['passwordReg']) > 20) {
				$errors[] = "Недопустимая длина пароля (от 6 до 20 символов)";
			}
		} else {
			$errors[] = 'Вы не отправили пароль';
		}

		if ($data['password_2Reg'] != $data['passwordReg']) {

			$errors[] = "Повторный пароль введен не верно!";
		}

		if (empty($errors)) {

			// Хешируем пароль
			$password = password_hash($data['passwordReg'], PASSWORD_DEFAULT);

			// добавляем в таблицу записи
			$result = mysqli_query(
				$link,
				"INSERT INTO `users` (`login`, `email`, `name`, `family`, `password`) VALUES
				(
					'{$data["loginReg"]}',
					'{$data["emailReg"]}',
					'{$data["nameReg"]}',
					'{$data["familyReg"]}',
					'{$password}'
				)"
			);
			if ($result) {
				echo '<div style="color: green; ">Вы успешно зарегистрированы! Можно <a href="login.php">авторизоваться</a>.</div><hr>';
			} else {
				echo '<div style="color: red; ">Не удалось зарегестрировать пользователя! Попробуйте позже!</div><hr>';
			}
		} else {
			// array_shift() извлекает первое значение массива array и возвращает его, сокращая размер array на один элемент. 
			echo '<div style="color: red; ">' . array_shift($errors) . '</div><hr>';
		}
	}
	?>

	<div class="container mt-4">
		<h2>Форма регистрации</h2>
		<form id="newUserWithVal" name="newUserWithVal" method="post">
			<div class="form-list">
				<div class="mt-4">
					<input class="form-control" type="text" name="loginReg" id="loginReg" placeholder="Введите логин">
					<span class="error"></span>
				</div>
				<div class="mt-4">
					<input class="form-control" type="email" name="emailReg" id="emailReg" placeholder="Введите Email">
					<span class="error"></span>
				</div>
				<div class="mt-4">
					<input class="form-control" type="text" name="nameReg" id="nameReg" placeholder="Введите имя">
					<span class="error"></span>
				</div>
				<div class="mt-4">
					<input class="form-control" type="text" name="familyReg" id="familyReg" placeholder="Введите фамилию">
					<span class="error"></span>
				</div>
				<div class="mt-4">
					<input class="form-control" type="password" name="passwordReg" id="passwordReg" placeholder="Введите пароль">
					<span class="error"></span>
				</div>
				<div class="mt-4">
					<input class="form-control" type="password" name="password_2Reg" id="password_2Reg" placeholder="Повторите пароль">
					<span class="error"></span>
				</div>
				<div class="mt-4">
					<button type="submit" class="btn btn-success" name="do_signup" id="do_signup">Зарегистрироваться</button>
				</div>
			</div>
		</form>
		<br>
		<p>Если вы зарегистрированы, тогда нажмите <a href="login.php">здесь</a>.</p>
		<p>Вернуться на <a href="./www/intro.php">главную</a>.</p>
	</div>
	<script src='./jsscripts/valid_signup.js?".time()."'></script>
</body>

</html>