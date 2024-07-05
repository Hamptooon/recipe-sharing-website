<?php
    require './admin.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/adminRecipePanel.css">
    <title>Document</title>
</head>
<body>
    <div class="recipe-panel">
        <h1 class="recipe-panel__title">Управление рецептами</h1>
        <div class="recipe-panel__nav">
            <!-- <a href="./recipeAddAdmin.php">Создать</a>
            <a href="./recipeDeleteAdmin.php">Удалить/Изменить</a> -->
            <a href="./recipeCategoriesDeleteAdmin.php">Редактировать категории</a>
            <a href="./recipesModerationAdmin.php">Модерация</a>
            <a href="./recipesChangeAdmin.php">Редактировать рецепты</a>
        </div>
    </div>
</body>
</html>