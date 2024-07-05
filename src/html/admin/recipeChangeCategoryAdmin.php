
<?php
    require '../../php/dbConnect.php'; 
    require './receiptCategoriesControlAdmin.php';  
    require '../../php/utility/utilityFunctions.php'; 
    session_start();
    $category_id = $_GET['category_id'];
    if ($category_id != false){
        $checkCategoryQuery = "SELECT * FROM recipe_categories WHERE category_id=$category_id"; 
        $result = $mysqli->query($checkCategoryQuery);
        $category = mysqli_fetch_assoc($result);
    }
   
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/categoryFormStyle.css">
        <link rel="stylesheet" href="../../css/alertStyle.css">
        <title>Изменение категории</title>
    </head>
    <body>
        <div class="category-form">
        <h2 class = "category-form-title">Изменение категории</h2>
            <form action="../../php/admin/recipeCategoryChangeAdminProcess.php" method = "post">
                <div>
                    <input type="hidden" value="<?=$category_id?>" name = "category_id">
                    <div>
                        <label class = "category-form-value-title" for="name">Название</label>
                    </div>
                    <input type="text" value ="<?=$category['category_name']?>" name="title" required>
                </div>
                <button type="submit" class = "category-btn">Изменить</button>
            </form>
        </div>
        <?php showAlert("categoryChangeMessage")?>
    </body>
</html>