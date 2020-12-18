<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Просмотр подробной информации со страницы отделений</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/af06815fdc.js"></script>
    <meta content="text/html; charset=utf-8">
</head>

<body>
    <?php
    require_once "../db.php"; // подключаем файл для соединения с БД
    session_start();

    ?>
    <div class="container pb-3 pt-3">
        <center>
            <h3>Показ подробной информации</h3>
        </center>
        <?php
        if (isset($_GET["Otdel_id"])) {
            $otdel = mysqli_query($link, "SELECT * FROM `otdelenia` WHERE `id` = '" . mysqli_real_escape_string($link, $_GET["Otdel_id"]) . "' LIMIT 1");
            $otd = mysqli_fetch_array($otdel);
            $count_otd = mysqli_num_rows($otdel);
        ?>
            <div class="row pt-3">
                <div class="col">
                    <h4><?php echo $otd['name'] ?>
                        <?php if (isset($_SESSION["auth"])) : ?>
                            <a href="./phpscripts/otdelDelete.php?delOtdel_id=<?php echo $otd['id']; ?>" class="btn fa fa-trash" style="color:blue;"></a>
                            <a href="./phpscripts/otdelCorrect.php?correctOtdel_id=<?php echo $otd['id']; ?>" class="btn fa fa-cog" style="color:blue;"></a>
                        <?php endif; ?>
                    </h4>
                    <p class="pt-0 text-center"> Адрес: <?php echo $otd['address'] ?> </p>
                    <p class="pt-0 text-center"> Контактный телефон: <?php echo $otd['phone'] ?> </p>
                </div>
                <div class="col">
                    <h6>Режим работы: </h6>
                    <p class="pt-0 text-center"> <?php echo $otd['rezhim_raboty'] ?> </p>
                </div>
            </div>
            <div class="row">
                <p>
                    <h4>Сотрудники отделения: <h4>
                </p>
            </div>
            <?php
            $otd_sotrud = mysqli_query($link, "SELECT * FROM sotrudniki WHERE otdel_ID = '" . mysqli_real_escape_string($link, $otd['id']) . "'");
            $count_otd_sotrud = mysqli_num_rows($otd_sotrud);
            $n = 1;
            if ($count_otd_sotrud >= 1) :
                while ($str = mysqli_fetch_array($otd_sotrud)) { ?>
                    <div class="otd mt-4">
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
                                    <br>Должность: <?php echo $str['dolzh'] ?> </p>
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
        } else if (isset($_GET["Sotrud_id"])) {
            $sotrud = mysqli_query($link, "SELECT * FROM `sotrudniki` WHERE `idSot` = '" . mysqli_real_escape_string($link, $_GET["Sotrud_id"]) . "' LIMIT 1");
            $str = mysqli_fetch_array($sotrud);
            ?>
            <div class="row mt-4">
                <div class="media ml-5">
                    <img class="mr-3" width="20%" src="../img/sotrudImg/<?php echo $str['image'] ?>">
                    <div class="media-body align-self-center">
                        <strong> <?php echo $str['name'] ?> </strong>
                        <?php if (isset($_SESSION["auth"])) : ?>
                            <a href="../phpscripts/sotrudDelete.php?del_id=<?php echo $str['idSot']; ?>" class="btn fa fa-trash" style="color:blue;"></a>
                            <a href="../phpscripts/sotrudCorrect.php?correct_id=<?php echo $str['idSot']; ?>?" class="btn fa fa-cog" style="color:blue;"></a>
                        <?php endif; ?>
                        <p>Дата рождения: <?php echo $str['birhDate'] ?>
                            <br>Дата вступления в нашу команду: <?php echo $str['rabDate'] ?>
                            <br>Должность: <?php echo $str['dolzh'] ?> </p>
                    </div>
                </div>
            </div>

            <?php
            $otd_query = mysqli_query($link, "SELECT * FROM otdelenia WHERE id = '" . mysqli_real_escape_string($link, $str['otdel_ID']) . "' LIMIT 1");
            $otd = mysqli_fetch_array($otd_query);
            $count_otd_query = mysqli_num_rows($otd_query);
            if ($count_otd_query) : ?>
                <div class="row mt-5">
                    <p>
                        <h4>Информация об отделении сотрудника: <h4>
                    </p>
                </div>
                <div class="row pt-3">
                    <div class="col">
                        <h4><?php echo $otd['name'] ?>
                            <?php if (isset($_SESSION["auth"])) : ?>
                                <a href="./phpscripts/otdelDelete.php?delOtdel_id=<?php echo $otd['id']; ?>" class="btn fa fa-trash" style="color:blue;"></a>
                                <a href="./phpscripts/otdelCorrect.php?correctOtdel_id=<?php echo $otd['id']; ?>" class="btn fa fa-cog" style="color:blue;"></a>
                            <?php endif; ?>
                        </h4>
                        <p class="pt-0 text-center"> Адрес: <?php echo $otd['address'] ?> </p>
                        <p class="pt-0 text-center"> Контактный телефон: <?php echo $otd['phone'] ?> </p>
                    </div>
                    <div class="col">
                        <h6>Режим работы: </h6>
                        <p class="pt-0 text-center"> <?php echo $otd['rezhim_raboty'] ?> </p>
                    </div>
                </div>
        <?php
            endif;
            echo '<hr align="center" width="100%" color="Grey" />';
        }
        ?>
    </div>
    <br>
    <p>Вернуться <a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>">обратно</a>.</p>
</body>

</html>