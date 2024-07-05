<?php
    require '../dbConnect.php';
    require '../db/dbFunctions.php';
    require '../utility/utilityFunctions.php';
    session_start();
    
    $title = $_POST['title'];
    $introduction = $_POST['introduction'];
    $cookingTimeHours = (int)$_POST['cooking_time_hours'];
    $cookingTimeMinutes = (int)$_POST['cooking_time_minutes'];
    $categoryId = $_POST['category'];
    
    $totalCookingTime = calculateTotalCookingTime($cookingTimeHours, $cookingTimeMinutes);
    
    $recipeId = insertRecipe($_SESSION['user_id'], $title, $introduction, $totalCookingTime, $categoryId, $mysqli, 'pending');
    
    if (is_string($recipeId)) {
        // Handle error
        echo $recipeId;
        exit;
    }
    
    $ingredientsNames = $_POST['ingredients_name'];
    $ingredientsQuantities = $_POST['ingredients_quantity'];
    insertIngredients($recipeId, $ingredientsNames, $ingredientsQuantities, $mysqli);
    
    handleImageUpload($title, $recipeId, $mysqli, $_FILES['main_image']);
    
    $stepDescriptions = $_POST['step_description'];
    insertRecipeSteps($recipeId, $stepDescriptions, $mysqli);
    
    $_SESSION["recipe_add_message"] = "<div class = 'alert__text-success'>Рецепт успешно добавлен</div>";
    $mysqli->close();
    if($_SESSION['is_admin']){
        header('Location: ../../html/admin/recipeAddAdmin.php');
    }
    else{
        header('Location: ../../html/client/profileCreateRecipe.php');
    }
    
    
?>
