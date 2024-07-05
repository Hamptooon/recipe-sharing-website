<?php
    session_start();
    require './profileControlPanel.php';
    require '../../php/utility/utilityFunctions.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/profileChanging.css">
    <link rel="stylesheet" href="../../css/alertStyle.css">
    <title>Профиль</title>
</head>
<body>
    <div class="moderate-container">
            <h2 class = "moderate-container-title">Мой аккаунт</h2>
            
            <div class="profile-form">
                <form action="../../php/client/profilePasswordChangingProcess.php" method="post">
                    <div class="profile-name">
                        <label class = "profile-form-value-title" for="password">Пароль</label><br>
                        <input type="password"  id="password" name="password" required>
                    </div>
                    <div class="profile-email">
                        <label class = "profile-form-value-title" for="password_repeat">Пароль повторно</label><br>
                        <input type="password"  id="password_repeat" name="password_repeat" required>
                    </div>
                    <button type = "submit">Сохранить</button>
                </form>
            </div>
    </div>
    <?php showAlert("password_change_message") ?>
</body>
</html>