<?php
    require '../../dbConnect.php';
    require '../../db/dbFunctions.php';
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $user_id = $_SESSION['user_id'];
        updateUserInfo($mysqli, $username, $email, $user_id);
        $_SESSION['change_profile_message'] = "<div class = 'alert__text-success'>Данные успешно изменены</div>";
        header('Location: ../../../html/client/profileChanging.php'); 

    }
?>