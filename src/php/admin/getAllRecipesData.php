<?php
    require '../../php/dbConnect.php';
    require '../utility/utilityFunctions.php';
    require '../db/dbFunctions.php';

    $response = array('success' => false, 'message' => '');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $recipe_result = $mysqli->query("SELECT * FROM recipes");
        $recipes = [];

        while ($recipe = $recipe_result->fetch_assoc()) {
            $recipe_id = $recipe['recipe_id'];

            $all_categories_result = $mysqli->query("SELECT * FROM recipe_categories");
            $categories = [];
            while ($category = $all_categories_result->fetch_assoc()) {
                $categories[] = $category;
            }

            $ingredients_result = $mysqli->query("SELECT * FROM ingredients WHERE recipe_id = $recipe_id");
            $ingredients = [];
            while ($ingredient = $ingredients_result->fetch_assoc()) {
                $ingredients[] = $ingredient;
            }

            $steps_result = $mysqli->query("SELECT * FROM recipe_steps WHERE recipe_id = $recipe_id");
            $steps = [];
            while ($step = $steps_result->fetch_assoc()) {
                $steps[] = $step;
            }

            $recipes[] = [
                'recipe_id' => $recipe['recipe_id'],
                'title' => $recipe['title'],
                'introduction' => $recipe['introduction'],
                'cooking_time_minutes' => $recipe['cooking_time_minutes'],
                'category_id' => $recipe['category_id'],
                'main_image_url' => $recipe['main_image_url'],
                'timestamp' => date('H:i:s d.m.Y'),
                'categories' => $categories,
                'ingredients' => $ingredients,
                'steps' => $steps
            ];
        }

        if (count($recipes) > 0) {
            $response['success'] = true;
            $response['recipes'] = $recipes;
        } else {
            $response['message'] = 'Рецепты не найдены';
        }
    } else {
        $response['message'] = 'Неверный запрос';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
?>