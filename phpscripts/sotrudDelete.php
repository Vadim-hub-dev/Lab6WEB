<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Форма удаления сотрудника</title>
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
        if (isset($data['del_id'])) {
            $sotrud = mysqli_query($link, "SELECT * FROM `sotrudniki` WHERE `idSot` = '{$data["del_id"]}'");
            $row = mysqli_fetch_array($sotrud);

            // Создаем массив для сбора ошибок
            $errors = array();

            if(!$sotrud){
                $errors[] = 'Произошла ошибка при удалении!';
            }


            if (empty($errors)) {
                if (unlink("../img/sotrudImg/{$row['image']}")) {
                    mysqli_query($link, "DELETE FROM `sotrudniki` WHERE `idSot` = '" . mysqli_real_escape_string($link, $data["del_id"]) . "'");
                    echo '<div style="color: green; ">Сотрудник успешно удалён!</div><hr>';
                    header('Location: ../www/otdel.php');
                }else{echo 'Не удалось удалить файл с сервера!';}
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