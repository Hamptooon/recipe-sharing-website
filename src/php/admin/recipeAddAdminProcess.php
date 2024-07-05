<?php
require '../dbConnect.php';
require '../db/dbFunctions.php';
require '../utility/utilityFunctions.php';
session_start();

if (!isset($_POST['title'], $_POST['introduction'], $_POST['cooking_time_hours'], $_POST['cooking_time_minutes'], $_POST['category'])) {
    echo json_encode(['success' => false, 'message' => 'Недостаточно данных', 'timestamp' => date('H:i:s d.m.Y')]);
    exit;
}

$title = $_POST['title'];
$introduction = $_POST['introduction'];
$cookingTimeHours = (int)$_POST['cooking_time_hours'];
$cookingTimeMinutes = (int)$_POST['cooking_time_minutes'];
$categoryId = $_POST['category'];

$totalCookingTime = calculateTotalCookingTime($cookingTimeHours, $cookingTimeMinutes);

$recipeId = insertRecipe($_SESSION['user_id'], $title, $introduction, $totalCookingTime, $categoryId, $mysqli, 'approved');

if (is_string($recipeId)) {
    echo json_encode(['success' => false, 'message' => $recipeId]);
    exit;
}

$ingredientsNames = $_POST['ingredients_name'];
$ingredientsQuantities = $_POST['ingredients_quantity'];
insertIngredients($recipeId, $ingredientsNames, $ingredientsQuantities, $mysqli);

$mainImageUrl = handleImageUpload($title, $recipeId, $mysqli, $_FILES['main_image']);

$stepDescriptions = $_POST['step_description'];
insertRecipeSteps($recipeId, $stepDescriptions, $mysqli);

$categoryName = getCategoryById($mysqli, $categoryId)["category_name"];

// Формирование ответа для клиента
$responseMessage = "Рецепт успешно добавлен";
echo json_encode([
    'success' => true,
    'message' => $responseMessage,
    'recipe_id' => $recipeId,
    'title' => $title,
    'category' => $categoryName,
    'introduction' => $introduction,
    'cookingTimeHours' => $cookingTimeHours,
    'cookingTimeMinutes' => $cookingTimeMinutes,
    'main_image_url' => $mainImageUrl,
    'timestamp' => date('H:i:s d.m.Y')
]);

$mysqli->close();
header('Content-Type: application/json');
?>
