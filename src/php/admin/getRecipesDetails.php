<?php
    require '../../php/dbConnect.php';
    require '../utility/utilityFunctions.php';
    require '../db/dbFunctions.php';

    $response = array('success' => false, 'message' => '');

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['recipe_id'])) {
        $recipe_id = $_GET['recipe_id'];

        // Fetch recipe details
        $recipe_result = $mysqli->query("SELECT * FROM recipes WHERE recipe_id = $recipe_id");
        $recipe = $recipe_result->fetch_assoc();

        if ($recipe) {
            $response['success'] = true;
            $response['recipe_id'] = $recipe['recipe_id'];
            $response['title'] = $recipe['title'];
            $response['introduction'] = $recipe['introduction'];
            $response['cooking_time_minutes'] = $recipe['cooking_time_minutes'];
            $response['category_id'] = $recipe['category_id'];
            $response['main_image_url'] = $recipe['main_image_url'];
            $response['timestamp'] = date('H:i:s d.m.Y');
            
            $all_categories_result = $mysqli->query("SELECT * FROM recipe_categories");
            $categories = [];
            while ($category = $all_categories_result->fetch_assoc()) {
                $categories[] = $category;
            }
            $response['categories'] = $categories;
            // Fetch ingredients
            $ingredients_result = $mysqli->query("SELECT * FROM ingredients WHERE recipe_id = $recipe_id");
            $ingredients = [];
            while ($ingredient = $ingredients_result->fetch_assoc()) {
                $ingredients[] = $ingredient;
            }
            $response['ingredients'] = $ingredients;

            // Fetch recipe steps
            $steps_result = $mysqli->query("SELECT * FROM recipe_steps WHERE recipe_id = $recipe_id");
            $steps = [];
            while ($step = $steps_result->fetch_assoc()) {
                $steps[] = $step;
            }
            $response['steps'] = $steps;
        } else {
            $response['message'] = 'Рецепт не найден';
        }
    } else {
        $response['message'] = 'Неверный запрос';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
?>
