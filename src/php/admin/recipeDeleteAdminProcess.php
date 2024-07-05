<?php
    require '../dbConnect.php';
    require '../db/dbFunctions.php';
    require '../utility/utilityFunctions.php';
    session_start();
    if(isset($_POST['recipe_ids'])){
        foreach($_POST['recipe_ids'] as $recipe_id){
            $recipe = getRecipe($mysqli, $recipe_id);
            deleteFile('../../uploads/recipeImages/' . $recipe['main_image_url']);
            deleteRecipe($mysqli, $recipe_id);
        }
    }
    $mysqli->close();
    $_SESSION["recipe_delete_message"] = "<div class = 'alert__text-success'>Данные успешно удалены</div>";
    if($_SESSION['is_admin']){
        header('Location: ../../html/admin/recipeDeleteAdmin.php');
    }
    else{
        if($_POST['recipe_type'] == 'pending'){
            header('Location: ../../html/client/profileRecipesPending.php');
        }
        else if($_POST['recipe_type'] == 'approved'){
            header('Location: ../../html/client/profileRecipesApproved.php');
        }
        else if($_POST['recipe_type'] == 'rejected'){
            header('Location: ../../html/client/profileRecipesRejected.php');
        }
        
    }
   
    
?>
