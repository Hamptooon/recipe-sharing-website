<?php
    // Инициализация сессии
    session_start();

    // Очистка всех переменных сессии
    session_unset();

    // Уничтожение сессии
    session_destroy();
    header('Location: ../../html/index.php'); 
?>