<?php
    require './dbConnect.php';
    session_start();
    if ($mysqli->connect_error) {
        die("Ошибка подключения к базе данных: " . $mysqli->connect_error);
    }

    else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

        $checkUserQuery = "SELECT * FROM users WHERE username = '$username'";
        $result = $mysqli->query($checkUserQuery);

        if ($result->num_rows > 0) {
            $_SESSION['message'] = 'Никнейм уже занят.';
            header('Location: ../html/register.php');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['message'] = 'Некорректный почтовый адрес.';
            header('Location: ../html/register.php');
        } else {
            $insertUserQuery = "INSERT INTO users (username, password_hash, email) VALUES ('$username', '$password', '$email')";
            mysqli_query($mysqli, $insertUserQuery);

            $insertedUserId = $mysqli->insert_id;

            $_SESSION['user_id'] = $insertedUserId;
            header('Location: ../html/index.php');
        }

        $mysqli->close();
    }
?>
