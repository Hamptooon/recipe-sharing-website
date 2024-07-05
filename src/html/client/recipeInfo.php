<?php
    session_start();
    require './header.php';
    require '../../php/dbConnect.php';
    require '../../php/db/dbFunctions.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['recipe_id'])) {

        
        $recipe_id = $_GET['recipe_id'];

        $recipeResult = $mysqli->query("SELECT * FROM recipes WHERE recipe_id = $recipe_id");

        if ($recipeResult->num_rows > 0) {
            $recipe = $recipeResult->fetch_assoc();
            $other_recipes_by_category_id = getRecipesbyCategoryId($mysqli, $recipe['category_id'], $recipe_id);
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

            $categoryResult = $mysqli->query("SELECT * FROM recipe_categories WHERE category_id = {$recipe['category_id']}");
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
        <link rel="stylesheet" href="../../css/recipeInfoStyle.css">
        <title>Информация о рецепте</title>
    </head>
    <body>

        <div class="recipe-container">
            <h2 class = "recipe-container-title">Рецепт</h2>
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
            <div id = "rating">asdasda</div>

            <h3>Поставьте оценку рецепту</h3>
        <form id="grade-set-form">
            <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
            <select name="grade" id="rating-select">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <button type="button" onclick="submitRating()">Отправить оценку</button>
        </form>
            <div>
                <div class = "title_other_recipes">Другие рецепты данной категории</div>
                <div class = "other_recipes">
                    <?php
                        while($another_recipe = $other_recipes_by_category_id->fetch_assoc()){
                            echo "<a href = recipeInfo.php?recipe_id={$another_recipe['recipe_id']}>{$another_recipe['title']}</a>";
                        }
                    ?>
                </div>

                </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>

            function submitRating() {
                        var form = $('#grade-set-form')[0];
                        var formData = new FormData(form);
                        for (const [key, value] of formData.entries()) {
                            console.log(`${key}: ${value}`);
                        }
                        $.ajax({
                            url: '../../php/admin/setGradeRecipe.php',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                if (data.success) {
                                    // console.log(data.grade);
                                    // $('#rating').text(data.grade);
                                } else {
                                    $('#grade-set-form').hide();
                                   $('#rating').text(data.new_rating);
                                   console.log(data.new_rating);

                                }
                            },
                            error: function(xhr, status, error) {
                                var errorMessage = xhr.status + ': ' + xhr.statusText;
                                console.log('Ошибка при выполнении запроса: ' + errorMessage);
                            }
                        });
                    }
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
