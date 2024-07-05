<?php
    require '../../php/dbConnect.php';
    require './recipeAdminControl.php';
    require '../../php/utility/utilityFunctions.php';
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/recipeFormStyle.css">
        <link rel="stylesheet" href="../../css/adminDeleteStyle.css">
        <link rel="stylesheet" href="../../css/alertStyle.css">
        <title>Изменение рецептов</title>
    </head>
    <body>
        <div class="recipe-form">
    
            <h2 class = "recipe-form-title">Изменение/Удаление рецептов</h2>
    
            <form action="../../php/admin/recipeDeleteAdminProcess.php" method="post">
                <div class="delete-form">
                    <?php
                        $result = $mysqli->query("SELECT * FROM recipes");
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class = "delete-elem">';
                            echo '<input type="checkbox" name="recipe_ids[]" value="' . $row['recipe_id'] . '"> ' . $row['title'] . "<a href ='./recipeChangeAdmin.php?recipe_id={$row['recipe_id']}' class = 'button'><img src = '../../img/logo/icons/changeIcon.png'></a>";
                            echo '</div>';
                        }
                        $mysqli->close();
                    ?>
                </div>
                
                <button type="submit" class = "delete-btn">Удалить рецепты</button>
                
            </form>
        </div>
        <?php showAlert("recipe_delete_message")?>
      
    </body>
</html>
