<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Форма удаления отделения</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta content="text/html; charset=utf-8">
</head>

<body>
    <?php
    require_once "../db.php"; // подключаем файл для соединения с БД
    session_start();
    if (isset($_SESSION['auth'])) {
        $data = $_GET;
        if (isset($data['delOtdel_id'])) {
            $otdel = mysqli_query($link, "SELECT * FROM otdelenia WHERE `id` = '" . mysqli_real_escape_string($link, $data["delOtdel_id"]) . "'");
            $row = mysqli_fetch_array($otdel);
            
            // Создаем массив для сбора ошибок
            $errors = array();

            if (!$otdel) {
                $errors[] = 'Произошла ошибка!';
            }

            if (empty($errors)) {
                mysqli_query($link, "DELETE FROM `otdelenia` WHERE `id` = '" . mysqli_real_escape_string($link, $data["delOtdel_id"]) . "'");
                echo '<div style="color: green; ">Отделение успешно удалено!</div><hr>';
                header('Location: ../www/otdel.php');
            } else {
                // array_shift() извлекает первое значение массива array и возвращает его, сокращая размер array на один элемент. 
                echo '<div style="color: red; ">' . array_shift($errors) . '</div><hr>';
            }
        }
    } else {
        echo '<h1 style="color:red">Эта функция доступна только для зарегестрированных пользователей!</h1>';
    }
    ?>
</body>

</html>