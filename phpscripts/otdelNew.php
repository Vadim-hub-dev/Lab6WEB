<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Форма добавления отделения</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/styleValid.css">
    <meta content="text/html; charset=utf-8">
</head>

<body>
    <?php
    require_once "../db.php"; // подключаем файл для соединения с БД
    session_start();
    if (isset($_SESSION['auth'])) {
        $data = $_POST;

        if (isset($data['newOtdel_name'])) {
            // Создаем массив для сбора ошибок
            $errors = array();

            if (!empty(trim($data["newOtdel_name"]))) {
                if (!preg_match('/^[a-zA-Zа-яёА-ЯЁ\s]+$/iu', $data["newOtdel_name"])) {
                    $errors[] = 'Не корректно введён отдел!';
                }
            } else {
                $errors[] = 'Вы не отправили название отдела';
            }

            if (!empty(trim($data["newRezhim_raboty"]))) {
                if (!preg_match('/^[a-zA-Zа-яёА-ЯЁ0-9\s\-\,\:\.]+$/iu', $data["newRezhim_raboty"])) {
                    $errors[] = 'Не корректно введен режим работы отделения!';
                }
            } else {
                $errors[] = 'Вы не отправили режим работы отделения!';
            }

            if (!empty(trim($data["address"]))) {
                if (!preg_match('/^[a-zA-Zа-яёА-ЯЁ0-9\s\-\,\:\.]+$/iu', $data["address"])) {
                    $errors[] = 'Не корректно введен адрес отделения!';
                }
            } else {
                $errors[] = 'Вы не отправили адрес отделения!';
            }

            if (!preg_match('/^(\+7|8|7)\([0-9]{3}\)-[0-9]{3}-[0-9]{2}-[0-9]{2}/', $data["phone"]) and !($data["phone"] == "")) {

                $errors[] = 'Неверно введен телефон отделения!';
            }

            if (isset($data["newDate_obr"])) {

                $date = Datetime::createFromFormat('Y-m-d', $data["newDate_obr"]);

                if (!$date) {
                    $errors[] = 'Дата образования отдела введена в неверном формате';
                }
            } else {
                $errors[] = 'Дата образования отдела не введена!';
            }


            if (!$errors) {
                // добавляем в таблицу записи
                mysqli_query(
                    $link,
                    "INSERT INTO `otdelenia` (`name`, `rezhim_raboty`, `date_obr`, `address`, `phone`) VALUES 
                    (
                        '{$data["newOtdel_name"]}', 
                        '{$data["newRezhim_raboty"]}', 
                        '{$data["newDate_obr"]}', 
                        '{$data["address"]}', 
                        '{$data["phone"]}'
                    )"
                );
                echo '<div style="color: green; ">Отделение успешно добавлено! Можно вернуться на <a href="../www/intro.php">главную</a>.</div><hr>';
            } else {
                // array_shift() извлекает первое значение массива array и возвращает его, сокращая размер array на один элемент. 
                echo '<div style="color: red; ">' . array_shift($errors) . '</div><hr>';
            }
        }
    } else {
        echo '<h1 style="color:red">Эта функция доступна только для зарегестрированных пользователей!</h1>';
    }
    ?>

    <div class="container mt-5">
        <h2>Форма внесения нового отделения</h2><br>
        <form id="newOtdelWithVal" method="post">
            <div class="form-list">
                <div>
                    <input class="form-control" type="text" id="newOtdel_name" name="newOtdel_name" maxlength="50" value="" placeholder="Введите название отдела">
                    <span class="error"></span>
                </div>
                <div class="mt-3">
                    <input class="form-control" type="text" id="newRezhim_raboty" name="newRezhim_raboty" maxlength="100" value="" placeholder="Введите режим работы отделения">
                    <span class="error"></span>
                </div>
                <div class="mt-3">
                    <label>Дата образования отделения:</label>
                    <input class="form-control" type="date" id="newDate_obr" name="newDate_obr">
                    <span class="error"></span>
                </div>
                <div class="mt-3">
                    <input class="form-control" type="text" id="address" name="address" maxlength="50" value="" placeholder="Введите адрес отдела">
                    <span class="error"></span>
                </div>
                <div class="mt-3">
                    <label>Введите номер в формате +7(ххх)-ххх-хх-хх, либо оставьте поле пустым:</label>
                    <input class="form-control" type="tel" id="phone" name="phone" value="">
                    <span class="error"></span>
                </div>
                <div class="mt-3">
                    <button id="do_newOtdel" name="do_newOtdel" class="btn btn-success" type="submit">Добавить отделение</button>
                </div>
            </div>
        </form>
        <br>
        <p>Вернуться в раздел <a href="../www/otdel.php">отделения</a>.</p>
    </div>
    <script src='../jsscripts/valid_newOtdel.js?".time()."'></script>
</body>

</html>