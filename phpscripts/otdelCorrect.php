<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Форма редактирования отделения</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/styleValid.css">
    <meta content="text/html; charset=utf-8">
</head>

<body>
    <?php
    require_once "../db.php"; // подключаем файл для соединения с БД
    session_start();
    $otdel = mysqli_query($link, "SELECT * FROM `otdelenia` WHERE `id` = '" . mysqli_real_escape_string($link, $_GET["correctOtdel_id"]) . "'");
    $otd = mysqli_fetch_array($otdel);
    $count_otd = mysqli_num_rows($otdel);
    if (isset($_SESSION['auth'])) {
        $data = $_POST;

        if (isset($data['newOtdel_name'])) {

            // Создаем массив для сбора ошибок
            $errors = array();

            if (!$count_otd) {
                $errors[] = 'Не найдено отделение для редактирования!';
            }


            if (!empty(trim($data["newOtdel_name"]))) {
                if (!preg_match('/^[a-zA-Zа-яёА-ЯЁ\s]+$/iu', $data["newOtdel_name"])) {
                    $errors[] = 'Не корректно введён новый отдел!';
                }
            } else {
                $errors[] = 'Вы не отправили название нового отдела';
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


            if (empty($errors)) {
                if ($count_otd) {
                    // добавляем в таблицу записи
                    mysqli_query(
                        $link,
                        "UPDATE `otdelenia` SET 
                    `name` = '{$data["newOtdel_name"]}', 
                    `rezhim_raboty` = '{$data["newRezhim_raboty"]}', 
                    `date_obr` = '{$data["newDate_obr"]}', 
                    `address` = '{$data["address"]}', 
                    `phone` = '{$data["phone"]}' 
                    WHERE `id` = '" . mysqli_real_escape_string($link, $otd['id']) . "'"
                    );
                    echo '<div style="color: green; ">Отделение успешно изменено! Можно вернуться на <a href="../www/intro.php">главную</a>.</div><hr>';
                    header('Location: ../www/otdel.php');
                }
            } else {
                // array_shift() извлекает первое значение массива array и возвращает его, сокращая размер array на один элемент. 
                echo '<div style="color: red; ">' . array_shift($errors) . '</div><hr>';
            }
        }
    } else {
        echo '<h1 style="color:red">Эта функция доступна только для зарегестрированных пользователей!</h1>';
    }
    ?>
    <div class="container-fluid pt-5">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="row justify-content-center">
                    <div class="col-6 ">
                        <h4><?php echo $otd['name'] ?>
                            <?php if (isset($_SESSION["auth"])) : ?>
                                <a href="../phpscripts/otdelDelete.php?delOtdel_id=<?php echo $otd['id']; ?>" class="btn fa fa-trash" style="color:blue;"></a>
                                <a href="../phpscripts/otdelCorrect.php?correctOtdel_id=<?php echo $otd['id']; ?>" class="btn fa fa-cog" style="color:blue;"></a>
                            <?php endif; ?>
                        </h4>
                        <p > Адрес: <?php echo $otd['address'] ?> </p>
                        <p > Контактный телефон: <?php echo $otd['phone'] ?> </p>
                        <h6>Режим работы: </h6>
                        <p > <?php echo $otd['rezhim_raboty'] ?> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <h2>Форма редактирования отделения</h2><br>
        <form id="correctOtdelWithVal" method="post">
            <div class="form-list">
                <label>
                    <h6>Теперь введите новые данные отдела! </h6>
                </label>
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
                    <button id="do_newOtdel" name="do_newOtdel" class="btn btn-success" type="submit">Внести изменения</button>
                </div>
            </div>
        </form>
        <br>
        <p>Вернуться в раздел <a href="../www/otdel.php">отделения</a>.</p>
    </div>
    <script src='../jsscripts/validOtdelCorrect.js?".time()."'></script>
</body>

</html>