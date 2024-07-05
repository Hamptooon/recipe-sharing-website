<?php
require '../dbConnect.php';
require '../db/dbFunctions.php';
require '../utility/utilityFunctions.php';
session_start();

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_POST['user_id'];
        $title = $_POST['title'];
        $category = $_POST['category'];
        $introduction = $_POST['introduction'];
        $cooking_time_hours = $_POST['cooking_time_hours'];
        $cooking_time_minutes = $_POST['cooking_time_minutes'];
        $main_image_url = $_POST['main_image_url'];
    
       $sql = "INSERT INTO recipes (user_id, title, introduction,status, cooking_time_minutes, category_id) VALUES ('1', 'вфцвфц', 'вфцвфц', 'approved', '23', '2')";
            
            if ($mysqli->query($sql) === FALSE) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Рецепт успешно восстановлен',
                    'timestamp' => date('H:i:s d.m.Y')
                ]);
            }
        
    
        echo json_encode([
                'success' => true,
                'message' => 'Рецепт успешно восстановлен',
                'timestamp' => date('H:i:s d.m.Y')
            ]);
       
    
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Неверный метод запроса',
            'timestamp' => date('H:i:s d.m.Y')
        ]);
    }
    
    $mysqli->close();
} catch (Exception $th) {
    echo json_encode([
        'success' => false,
        'message' => $th->getMessage(),
        'timestamp' => date('H:i:s d.m.Y')
    ]);
}


?>
