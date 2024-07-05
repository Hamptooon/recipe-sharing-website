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
    <link rel="stylesheet" type="text/css" href="../../css/styleComments.css">
    <link rel="stylesheet" href="../../css/alertStyle.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-hW5S6+zzn1u9zjNiC2QR5+3JY6pBkCFan7koqb0I5v9XXD1mlsG57Fw5u5ZxKO5X" crossorigin="anonymous">
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
                <?php $result = getRejectedUserRecipes($mysqli, $_SESSION['user_id']);?>
                <?php if($result->num_rows>0):?>
                <input type="hidden" value = "rejected" name = "recipe_type">
                <div class="delete-form">
                    <?php
                        $result = getRejectedUserRecipes($mysqli, $_SESSION['user_id']);
                        
                        while ($row = $result->fetch_assoc()) {
                            $notes = $mysqli->query("SELECT * FROM moderate_recipe_notes WHERE recipe_id = {$row['recipe_id']}");
                            echo '<div class = "delete-elem">';
                            echo '<input type="checkbox" name="recipe_ids[]" value="' . $row['recipe_id'] . '"> ' . $row['title'] . "<a href ='./recipeChange.php?recipe_id={$row['recipe_id']}' class = 'button'><img src = '../../img/logo/icons/changeIcon.png'></a>";
                            $htmlResults .= '<div class="faq">';
                            $htmlResults .= '<div class="faq-item">';
                            $htmlResults .= '<input class="faq-input" type="checkbox" id="faq_1">';
                            $htmlResults .=   '<label class="faq-title" for="faq_1">Причина отклонения</label>';
                            $htmlResults .=   '<div class="faq-text">';
                            while ($note = $notes->fetch_assoc()) {
                                $htmlResults .=       '<p><span class = "faq-username">Admin:   </span>' . $note['note'].'</p>';
                            }
                            
                            
                            $htmlResults .=    '</div>';
                            $htmlResults .='</div>';
                            $htmlResults .='</div>';
                            
                            echo '</div>';
                            echo $htmlResults;
                            echo '<a class = "send-recipe-btn" href = "../../php/client/sendRecipeForModerate.php?recipe_id='. $row['recipe_id'] . '">Отправить на модерацию</a>';
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
        <?php showAlert("recipe_status_message")?>
</body>
</html>