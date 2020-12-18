<!DOCTYPE html>
<html lang="ru">
<head>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<meta content="text/html; charset=utf-8">
</head>
<body>

<?php 
require_once "db.php"; // подключаем файл для соединения с БД
session_start();
mysqli_close($link);

// Производим выход пользователя
if (!empty($_SESSION['auth']) and $_SESSION['auth']) 
{
		session_destroy(); //разрушаем сессию для пользователя

		//Удаляем куки авторизации путем установления времени их жизни на текущий момент:
		setcookie('login', '', time()); //удаляем логин
		setcookie('key', '', time()); //удаляем ключ
}

// Редирект на главную страницу
header('Location: ./www/intro.php');
?>
</body>
</html>