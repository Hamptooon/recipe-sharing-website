<?php
    require '../dbConnect.php';
    require '../db/dbFunctions.php';
    require '../utility/utilityFunctions.php';
    session_start();

    if (isset($_POST['recipe_id'])) {
        $recipe_id = $_POST['recipe_id'];
        $recipe = getRecipe($mysqli, $recipe_id);

        if ($recipe) {
            // deleteFile('../../uploads/recipeImages/' . $recipe['main_image_url']);
            deleteRecipe($mysqli, $recipe_id);

            echo json_encode([
                'success' => true,
                'message' => 'Рецепт успешно удален',
                'title' => $recipe['title'],
                'recipe' => $recipe,
                'timestamp' => date('H:i:s d.m.Y')
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Рецепт не найден',
                'timestamp' => date('H:i:s d.m.Y')
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Не указан ID рецепта',
            'timestamp' => date('H:i:s d.m.Y')
        ]);
    }

    $mysqli->close();
    header('Content-Type: application/json');
?>
