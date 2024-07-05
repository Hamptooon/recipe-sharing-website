<?php
    require '../../php/dbConnect.php';

    $category_ids = $_POST['category_ids'];

    if (!empty($category_ids)) {
        $ids = implode(',', array_map('intval', $category_ids));
        
        // Получаем названия категорий перед удалением
        $query = "SELECT category_name FROM recipe_categories WHERE category_id IN ($ids)";
        $result = $mysqli->query($query);
        $deleted_category_names = [];
        
        while ($row = $result->fetch_assoc()) {
            $deleted_category_names[] = $row['category_name'];
        }
        header('Content-Type: application/json');
        // Удаляем категории
        $query = "DELETE FROM recipe_categories WHERE category_id IN ($ids)";
        if ($mysqli->query($query)) {
            echo json_encode(['success' => true,'message' => 'Категории удалены успешно', 'deleted_category_names' => $deleted_category_names, 'timestamp' => date('H:i:s d.m.Y')]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Ошибка при удалении категорий', 'timestamp' => date('H:i:s d.m.Y')]);
        }
        
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Категории не выбраны', 'timestamp' => date('H:i:s d.m.Y')]);
    }

    $mysqli->close();
?>
