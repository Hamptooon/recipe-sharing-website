<?php
    session_start();
    require './profileControlPanel.php';
    require  '../../php/dbConnect.php';
    require '../../php/db/dbFunctions.php';
    require '../../php/utility/utilityFunctions.php';
    $user_id = $_SESSION['user_id'];
 
    $userInfo = getUserInfo($mysqli, $user_id);
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
                <form action="../../php/client/profileChangeDataProcess.php" method="post">
                    <div class="profile-name">
                        <label class = "profile-form-value-title" for="title">Ваше имя</label><br>
                        <input type="text" value="<?php echo $userInfo['username']?>" id="username" name="username" required>
                    </div>
                    <div class="profile-email">
                        <label class = "profile-form-value-title" for="title">Email</label><br>
                        <input type="text" value="<?php echo $userInfo['email']?>" id="email" name="email" required>
                    </div>
                    <button type = "submit">Сохранить</button>
                </form>
            </div>
        </div>
        <?php showAlert("change_profile_message") ?>
</body>
</html>