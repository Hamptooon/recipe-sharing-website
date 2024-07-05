<?php
   require '../../php/dbConnect.php'; 
   require './receiptCategoriesControlAdmin.php';
   require '../../php/utility/utilityFunctions.php';
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/categoryFormStyle.css">
        <link rel="stylesheet" href="../../css/alertStyle.css">
        <title>Добавление категории</title>
    </head>
    <body>
        <div class="category-form">
            <h2 class = "category-form-title">Добавление категории</h2>
            <form action="../../php/admin/recipeCategoryAddAdminProcess.php" method="post" enctype="multipart/form-data">
                <div class="category-name">
                    <div><label class = "category-form-value-title" for="title">Название категории</label></div>
                    <input type="text" placeholder="Название" id="title" name="title" required>
                </div>
                <button type="submit" class = "category-btn">Добавить</button>
            </form>
        </div>
        <?php showAlert("categoryAddMessage")?>
    </body>
</html>
