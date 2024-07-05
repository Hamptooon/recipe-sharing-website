<?php
    require '../dbConnect.php';
    require '../db/dbFunctions.php';
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'];
        $password_repeat = $_POST['password_repeat'];
        $user_id = $_SESSION['user_id'];
        if($password == $password_repeat){
            updateUserPassword($mysqli, $password, $user_id);
            $_SESSION['password_change_message'] = "<div class = 'alert__text-success'>Данные успешно изменены</div>";
        }
        else{
            $_SESSION['password_change_message'] = "<div class = 'alert__text-failure'>Пароли не совпадают</div>";

        }
        
        header('Location: ../../../html/client/profilePasswordChanging.php'); 

    }
?>