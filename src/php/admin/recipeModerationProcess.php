<?php
    require '../dbConnect.php';
    require '../db/dbFunctions.php';
    session_start();
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['recipe_id'], $_POST['action'])) {
        $recipe_id = $_POST['recipe_id'];
        $action = $_POST['action'];

        if ($action === 'approve') {
            approveRecipe($mysqli, $recipe_id);
            $_SESSION["recipe_moderate_message"] = "<div class = 'alert__text-success'>Рецепт одобрен</div>";
        } elseif ($action === 'reject' && isset($_POST['note'])) {
            $note = $_POST['note'];
            rejectRecipe($mysqli, $recipe_id);
            insertModerateRecipeNote($mysqli, $recipe_id, $note);
            $_SESSION["recipe_moderate_message"] = "<div class = 'alert__text-success'>Рецепт успешно отклонен</div>";
        }else {
            $_SESSION["recipe_moderate_message"] = "<div class = 'alert__text-failure'>Неверное действие</div>";
        }
    } else {
        $_SESSION["recipe_moderate_message"] = "<div class = 'alert__text-failure'>Ошибка!</div>";
    }
    header("Location: ../../html/admin/recipesModerationAdmin.php");

    $mysqli->close();
?>
