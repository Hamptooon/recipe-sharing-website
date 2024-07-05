<?php
    require '../../php/dbConnect.php';
    require '../utility/utilityFunctions.php';
    require '../db/dbFunctions.php';
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $recipe_id = $_POST['recipe_id'];
        $title = $_POST['title'];
        $introduction = $_POST['introduction'];
        $cooking_time_hours = (int)$_POST['cooking_time_hours'];
        $cooking_time_minutes = (int)$_POST['cooking_time_minutes'];
        $category_id = $_POST['category'];
        $new_ingredients_names = $_POST['new_ingredients_name'];
        $new_ingredients_quantities = $_POST['new_ingredients_quantity'];
        $ingredients_names = $_POST['ingredients_name'];
        $ingredients_quantities = $_POST['ingredients_quantity'];
        $new_step_descriptions = $_POST['new_step_description'];
        $step_descriptions = $_POST['step_description'];
        $total_cooking_time = calculateTotalCookingTime($cooking_time_hours, $cooking_time_minutes);

        updateRecipeImage($mysqli, $recipe_id, $_FILES['main_image'], $title);

        updateRecipe($mysqli, $recipe_id, $title, $introduction, $total_cooking_time,$category_id);

        updateIngredientsByRecipeId($mysqli, $recipe_id, $ingredients_names, $ingredients_quantities);
        $step_number = updateStepsByRecipeId($mysqli, $recipe_id, $step_descriptions);

        insertNewIngredients($mysqli, $recipe_id, $new_ingredients_names, $new_ingredients_quantities);
        insertNewSteps($mysqli, $recipe_id,$new_step_descriptions, $step_number);
        $_SESSION["recipe_change_message"] = "<div class = 'alert__text-success'>Рецепт успешно изменен</div>";
        if($_SESSION['is_admin']){
            header('Location: ../../html/admin/recipeChangeAdmin.php?recipe_id='. $recipe_id);
        }
        else{
            header('Location: ../../html/client/recipeChange.php?recipe_id='. $recipe_id);
        }
        
        
        
    }
?>
