<?php
    session_start();
    require './profileControlPanel.php';
    require '../../php/dbConnect.php';
    require '../../php/db/dbFunctions.php';
    require '../../php/utility/utilityFunctions.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/profileChanging.css">
    <link rel="stylesheet" href="../../css/alertStyle.css">
    <title>Профиль</title>
</head>
<body>
    <div class="moderate-container">
            <h2 class = "moderate-container-title">Мой аккаунт</h2>
            <div class="profile-panel__nav">
                <a href="./profileRecipesApproved.php">Опубликованные</a>
                <a href="./profileRecipesPending.php">На модерации</a>
                <a href="./profileRecipesRejected.php">Отклоненные</a>
                <a href="./profileCreateRecipe.php">Создать рецепт</a>
            </div>
            <form action="../../php/admin/recipeDeleteAdminProcess.php" method="post">
                <?php $result = getApprovedUserRecipes($mysqli, $_SESSION['user_id']);?>
                <?php if($result->num_rows>0):?>
                <input type="hidden" value = "approved" name = "recipe_type">
                <div class="delete-form">
                    <?php
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class = "delete-elem">';
                            echo '<input type="checkbox" name="recipe_ids[]" value="' . $row['recipe_id'] . '"> <a href = "./recipeInfo.php?recipe_id='. $row['recipe_id']. '">'.$row['title'] . '</a>' ;
                            echo '</div>';
                        }
                        $mysqli->close();
                    ?>
                </div>
                
                <button type="submit" class = "delete-btn">Удалить рецепты</button>
                <?php else:?>
                <div>Рецепты не найдены...</div>
                <?php endif;?>
            </form>
        </div>
        <?php showAlert("recipe_delete_message")?>
</body>
</html>