<?php
    require '../../php/dbConnect.php';

    $title = $_POST['title'];

    // SQL Injection protection
    $title = $mysqli->real_escape_string($title);

    $query = "INSERT INTO recipe_categories (category_name) VALUES ('$title')";
    if ($mysqli->query($query)) {
        $category_id = $mysqli->insert_id;
        header('Content-Type: application/json');
        echo json_encode(['success' => true,'message' => "Категория успешно добавлена", 'category_id' => $category_id, 'category_name' => $title, 'timestamp' => date('H:i:s d.m.Y')]);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['success' => false,'message' => "Ошибка при добавлении категории", 'timestamp' => date('H:i:s d.m.Y')]);
    }

    $mysqli->close();
?>
