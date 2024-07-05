<?php
    require '../../php/dbConnect.php';
    require './recipeAdminControl.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['recipe_id'])) {
        $recipe_id = $_GET['recipe_id'];

        $recipeResult = $mysqli->query("SELECT * FROM recipes WHERE recipe_id = $recipe_id");

        if ($recipeResult->num_rows > 0) {
            $recipe = $recipeResult->fetch_assoc();

            $ingredientsResult = $mysqli->query("SELECT * FROM ingredients WHERE recipe_id = $recipe_id");
            $ingredients = [];
            while ($ingredient = $ingredientsResult->fetch_assoc()) {
                $ingredients[] = $ingredient;
            }

            $stepsResult = $mysqli->query("SELECT * FROM recipe_steps WHERE recipe_id = $recipe_id");
            $steps = [];
            while ($step = $stepsResult->fetch_assoc()) {
                $steps[] = $step;
            }

            $categoryResult = $mysqli->query("SELECT * FROM recipes_categories WHERE category_id = {$recipe['category_id']}");
            if ($categoryResult->num_rows > 0) {
                $category = $categoryResult->fetch_assoc();
            } else {
                $category = ['category_name' => 'Неизвестно'];
            }
        } else {
            echo "Рецепт не найден.";
            exit;
        }
    } else {
        echo "Не указан ID рецепта.";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/adminModerationStyle.css">
        <title>Модерация рецепта</title>
    </head>
    <body>

        <div class="moderate-container">
            <h2 class = "moderate-container-title">Модерация рецепта</h2>
            <div class="recipe-info">
                <div class = "recipe-info-title">Название</div>
                <h3 class = "recipe-info-name"><?php echo $recipe['title']; ?></h3>
                <div class = "recipe-info-title">Описание</div>
                <div class="recipe-info-introduction"><?php echo $recipe['introduction']; ?></div>
                <div class = "recipe-info-title">Изображение</div>
                <div class="photo-file-download">
                        <div class ="photo-download">
                            <?php
                                echo "<img class = 'photo-download-img' src = '../../uploads/recipeImages/{$recipe['main_image_url']}'>";
                            ?>
                        </div>
                </div>
                <span class="recipe-info-title">Время приготовления:</span><?php echo formatCookingTime($recipe['cooking_time_minutes']); ?>
                <span class="recipe-info-title">Категория:</span> <?php echo $category['category_name']; ?>
                
                <h4 class = "recipe-info-title">Ингредиенты</h4>
                <ul>
                    <?php foreach ($ingredients as $ingredient) : ?>
                        <li><?php echo $ingredient['name']; ?> - <?php echo $ingredient['quantity_in_grams']; ?> г</li>
                    <?php endforeach; ?>
                </ul>
                <h4 class = "recipe-info-title">Пошаговый рецепт приготовления</h4>
                <?php $step_number = 1?>
                <div class="recipe-info-steps">
                    <?php foreach ($steps as $step) : ?>
                        <div class = "recipe-info-step">
                            <div class = "recipe-info-step-title">Шаг <?php echo $step_number ?></div>
                            <?php echo $step['description']; ?>
                        </div>
                        <?php $step_number++?>
                    <?php endforeach; ?>
                </div>
               
            </div>
            <form action="../../php/admin/recipeModerationProcess.php" method="post">
                <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">
                <input type="hidden" name="action" value="">
                <div class="moderate-btns">
                    <button type="submit" class = "moderate-btn" onclick="setAction('approve')">Принять</button>
                    <button type="button" class = "moderate-btn" onclick="rejectRecipe()">Отклонить</button>
                    <button type="button" class = "moderate-btn" onclick="editRecipe()">Изменить</button>
                </div>
            </form>
        </div>

        <script>
            function setAction(action) {
                document.querySelector('input[name="action"]').value = action;
                document.querySelector('form').submit();
            }
            function rejectRecipe() {
                var reason = prompt("Введите причину отказа:");
                if (reason !== null) {
                    var noteInput = document.createElement('input');
                    noteInput.type = 'hidden';
                    noteInput.name = 'note';
                    noteInput.value = reason;
                    document.querySelector('form').appendChild(noteInput);
                    document.querySelector('input[name="action"]').value = 'reject';

                    document.querySelector('form').submit();
                }
            }

            function editRecipe() {
                window.location.href = "recipeChangeAdmin.php?recipe_id=<?php echo $recipe['recipe_id']; ?>";
            }
        </script>

    </body>
</html>

<?php
    $mysqli->close();
?>

<?php
function formatCookingTime($totalMinutes) {
    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;

    return sprintf('%02d:%02d', $hours, $minutes);
}
?>
