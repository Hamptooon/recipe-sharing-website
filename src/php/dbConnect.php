<?php
    $mysqli=mysqli_connect("localhost","root","");
	mysqli_select_db($mysqli,"receipts_site");
	mysqli_set_charset($mysqli,"utf8");
    if ($mysqli->connect_error) {
        die("Ошибка подключения к базе данных: " . $mysqli->connect_error);
    }
?>