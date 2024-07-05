<?php
    require '../db/dbFunctions.php';
    require '../dbConnect.php';
    session_start();
    if(isset($_GET['recipe_id'])){
        $recipe_id = $_GET['recipe_id'];
        pendingRecipe($mysqli, $recipe_id);
        $_SESSION['recipe_status_message'] = "<div class = 'alert__text-success'>Рецепт отправлен на модерацию</div>";
        header('Location: ../../html/client/profileRecipesRejected.php');
    }
?>