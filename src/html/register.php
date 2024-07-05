<?php
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Регистрация и вход</title>
    <link rel="stylesheet" type="text/css" href="../css/logRegStyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald&family=Rubik+Wet+Paint&display=swap" rel="stylesheet">
<body>
    <div class="login-content">
        <video class="video-background" src="../video/food.mp4" autoplay muted loop></video>
                <div class="logo-container">
                    <div class="brand">
                        <a href="./index.php" class="brand__title"><span class = "text__orange">Taste</span><span class = "text__yellow">Palette</span></a>
                    </div>
                </div>
            <div class="log-container">
                <div class = "log-header">
                    <h1>Регистрация</h1>
                </div>
                <form action="../php/registerProcess.php" method="post">
                    <input class = "log-input" type="text" name="username" placeholder="Логин" required maxlength="15">
                    <input class = "log-input" type="text" name="email" placeholder="Почта" required minlength = "5" maxlength="30">
                    <input class = "log-input" type="password" name="password" placeholder="Пароль" required minlength="6" maxlength="15">
    
                    <?php
                        if($_SESSION['message']){
                            echo '
                                <div class="alert">
                                    <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>'.
                                    $_SESSION['message'].'
                                </div>
                            ';
                        }
                        unset($_SESSION['message']);
                    ?>
    
                    <button type="submit">Зарегистрироваться</button>
                </form>
    
                <div class = "log-invite">Уже есть аккаунт? <a href = "./login.php" class = "invite-link">Войти</a></div>
            </div>
    </div>
</body>
</html>
