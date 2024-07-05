<?php
require '../../php/dbConnect.php';

$category_id = $_POST['category_id'];
$title = $_POST['title'];

// SQL Injection protection
$category_id = intval($category_id);
$title = $mysqli->real_escape_string($title);
$select_query = "SELECT * FROM recipe_categories WHERE category_id = $category_id";
$result = $mysqli->query($select_query);
$category = $result->fetch_assoc();
$query = "UPDATE recipe_categories SET category_name = '$title' WHERE category_id = $category_id";
if ($mysqli->query($query)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true,'message' => 'Категория успешно изменена','category_id' => $category_id,'oldTitle' => $category['category_name'], 'title' => $title, 'timestamp' => date('H:i:s d.m.Y')]);
} else {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Ошибка при обновлении категории', 'timestamp' => date('H:i:s d.m.Y')]);
}

$mysqli->close();
?>
