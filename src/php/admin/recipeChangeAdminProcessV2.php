<?php
require '../../php/dbConnect.php';
require '../utility/utilityFunctions.php';
require '../db/dbFunctions.php';
session_start();

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_id = $_POST['recipe_id'];
    $title = $_POST['title'];
    $introduction = $_POST['introduction'];
    $cooking_time_hours = (int)$_POST['cooking_time_hours'];
    $cooking_time_minutes = (int)$_POST['cooking_time_minutes'];
    $category_id = $_POST['category'];
    $new_ingredients_names = $_POST['new_ingredients_name'] ?? [];
    $new_ingredients_quantities = $_POST['new_ingredients_quantity'] ?? [];
    $ingredients_names = $_POST['ingredients_name'] ?? [];
    $ingredients_quantities = $_POST['ingredients_quantity'] ?? [];
    $new_step_descriptions = $_POST['new_step_description'] ?? [];
    $step_descriptions = $_POST['step_description'] ?? [];
    $total_cooking_time = calculateTotalCookingTime($cooking_time_hours, $cooking_time_minutes);
    $main_image_url = '';

    $result = $mysqli->query("SELECT title, introduction, cooking_time_minutes, category_id, main_image_url FROM recipes WHERE recipe_id = $recipe_id");
    $old_recipe = $result->fetch_assoc();

    $changed_fields = [];

    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
        $main_image_url = updateRecipeImage($mysqli, $recipe_id, $_FILES['main_image'], $title);
    }

    if ($title !== $old_recipe['title']) {
        $changed_fields['title'] = ['old' => $old_recipe['title'], 'new' => $title];
    }
    if ($introduction !== $old_recipe['introduction']) {
        $changed_fields['introduction'] = ['old' => $old_recipe['introduction'], 'new' => $introduction];
    }
    if ($total_cooking_time != $old_recipe['cooking_time_minutes']) {
        $changed_fields['cooking_time'] = ['old' => $old_recipe['cooking_time_minutes'], 'new' => $total_cooking_time];
    }
    if ($category_id != $old_recipe['category_id']) {
        $changed_fields['category'] = ['old' => getCategoryById($mysqli, $old_recipe['category_id'])['category_name'], 'new' => getCategoryById($mysqli, $category_id)['category_name']];
    }
    if ($main_image_url !== $old_recipe['main_image_url']) {
        $changed_fields['main_image_url'] = ['old' => $old_recipe['main_image_url'], 'new' => $main_image_url];
    }

    $stmt = $mysqli->prepare("UPDATE recipes SET title = ?, introduction = ?, cooking_time_minutes = ?, category_id = ? WHERE recipe_id = ?");
    $stmt->bind_param("ssiii", $title, $introduction, $total_cooking_time, $category_id, $recipe_id);
    $stmt->execute();

    updateIngredientsByRecipeId($mysqli, $recipe_id, $ingredients_names, $ingredients_quantities);
    $step_number = updateStepsByRecipeId($mysqli, $recipe_id, $step_descriptions);
    insertNewIngredients($mysqli, $recipe_id, $new_ingredients_names, $new_ingredients_quantities);
    insertNewSteps($mysqli, $recipe_id, $new_step_descriptions, $step_number);

    $response['success'] = true;
    $response['message'] = 'Рецепт успешно изменен';
    $response['changed_fields'] = $changed_fields;
    $response['main_image_url'] = $main_image_url;
    $response['recipe_id'] = $recipe_id;
    $response['success'] = true;
    $response['title'] = $title;
    $response['introduction'] = $introduction;
    $response['cookingTimeHours'] = $cooking_time_hours;
    $response['cookingTimeMinutes'] = $cooking_time_minutes;
    $response['timestamp'] = date('H:i:s d.m.Y');
}

header('Content-Type: application/json');
echo json_encode($response);
?>
