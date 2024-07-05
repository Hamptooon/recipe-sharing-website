<?php
    require './header.php';
    session_start();
    if (!isset($_SESSION["user_id"])){
        header('Location: ../login.php');
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/profileControlPanel.css">
    <title>Document</title>
</head>
<body>
    <div class="profile-panel">
        <h1 class="profile-panel__title">Мой аккаунт</h1>
        <div class="profile-panel__nav">
            <a href="./profileChanging.php">Редактировать профиль</a>
            <a href="./profilePasswordChanging.php">Сменить пароль</a>
            <a href="./profileRecipesApproved.php">Мои Рецепты</a>
        </div>
    </div>
</body>
</html>