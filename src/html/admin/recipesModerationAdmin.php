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
        <link rel="stylesheet" href="../../css/moderarionRecipeFormStyle.css">
        <link rel="stylesheet" href="../../css/adminModerationStyle.css">
        <link rel="stylesheet" href="../../css/alertStyle.css">
        <title>Модерация рецептов</title>
    </head>
    <body>
        <div class="recipe-form">
            <h2 class = "recipe-form-title">Модерация рецептов</h2>
                <div class="moderate-form">
                    <?php
                        $result = $mysqli->query("SELECT * FROM recipes WHERE status = 'pending'");
                        while ($row = $result->fetch_assoc()) {
                            echo "<a href ='./recipeModerationAdmin.php?recipe_id={$row['recipe_id']}'>" . $row['title'] . '</a>'.'<br>';
                        }
                        $mysqli->close();
                    ?>
                </div>
        </div>
        <?php showAlert("recipe_moderate_message") ?>
    </body>
</html>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/recipeInfoStyle.css">
    <title>Информация о рецепте</title>
</head>
<body>
    <div class="recipe-container">
        <!-- Существующий код -->
        <!-- <h3>Поставьте оценку рецепту</h3>
        <select id="rating">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <button onclick="submitRating()">Отправить оценку</button>
        
        <!-- Другие существующие элементы -->

    </div>
    <script>
        function submitRating() {
            var rating = document.getElementById('rating').value;
            var recipe_id = <?php echo $recipe_id; ?>;
            
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'rateRecipe.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert('Спасибо за вашу оценку!');
                }
            };
            xhr.send('recipe_id=' + recipe_id + '&rating=' + rating);
        }
    </script> -->
</body>
</html>

