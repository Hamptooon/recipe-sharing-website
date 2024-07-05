<?php
    require './dbConnect.php';
    include './admin/adminInfo.php';
    session_start();
    

    if ($mysqli->connect_error) {
        die("Ошибка подключения к базе данных: " . $mysqli->connect_error);
    }

    else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];


        $checkUserQuery = "SELECT * FROM users WHERE username = '$username'";
        
        $result = $mysqli->query($checkUserQuery);
        $user = mysqli_fetch_assoc($result);
        
            if (password_verify($password, $user['password_hash'])) {

                $_SESSION['user_id'] = $user['user_id'];
                if($username == $adminLogin){
                    $_SESSION['is_admin'] = TRUE;
                    header('Location: ../html/admin/recipeAddAdmin.php');
                }
                else{
                    header('Location: ../html/index.php');
                }
               
            } else {
                $_SESSION['message'] = 'Неправильный никнейм или пароль.';
                
                header('Location: ../html/login.php');
            }
        
        
        $mysqli->close();
    }
?>
