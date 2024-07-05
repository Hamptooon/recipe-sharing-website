<?php
    session_start();
    require '../../php/dbConnect.php';
    require '../../php/utility/utilityFunctions.php';
    require './profileControlPanel.php';
    if (isset($_GET['recipe_id'])) {
        $recipe_id = $_GET['recipe_id'];

        $recipe_result = $mysqli->query("SELECT * FROM recipes WHERE recipe_id = $recipe_id");
        $recipe = $recipe_result->fetch_assoc();

        if ($recipe) {
            $ingredients_result = $mysqli->query("SELECT * FROM ingredients WHERE recipe_id = $recipe_id");
            $ingredients = [];
            while ($ingredient = $ingredients_result->fetch_assoc()) {
                $ingredients[] = $ingredient;
            }

            $steps_result = $mysqli->query("SELECT * FROM recipe_steps WHERE recipe_id = $recipe_id");
            $steps = [];
            while ($step = $steps_result->fetch_assoc()) {
                $steps[] = $step;
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
        <link rel="stylesheet" href="../../css/recipeFormStyle.css">
        <link rel="stylesheet" href="../../css/alertStyle.css">
        <link rel="stylesheet" href="../../css/profileChanging.css">
        <title>Изменение рецепта</title>
    </head>
    <body>
    <div class="moderate-container">
    <h2 class = "recipe-form-title">Изменение рецепта</h2>
            <div class="recipe-form">
                <form action="../../php/admin/recipeChangeAdminProcess.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">
                    <div class="recipe-name">
                        <label class = "recipe-form-value-title" for="title">Название блюда:</label>
                        <input type="text" id="title" name="title" value="<?php echo $recipe['title']; ?>" required>
                    </div>
                    <div class="recipe-category">
                        <label class = "recipe-form-value-title" for="category">Категория:</label>
                        <select id="category" name="category" required>
                            <?php
                                $categories_result = $mysqli->query("SELECT * FROM recipe_categories");
                                while ($category = $categories_result->fetch_assoc()) {
                                    $selected = ($category['category_id'] == $recipe['category_id']) ? 'selected' : '';
                                    echo "<option value=\"{$category['category_id']}\" $selected>{$category['category_name']}</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div>
                        <div><label class = "recipe-form-value-title" for="introduction">Описание</label></div>
                        <textarea id="introduction" name="introduction" required><?php echo $recipe['introduction']; ?></textarea>
                    </div>
                    <div class="recipe-form-time">
                        <div><label class = "recipe-form-value-title" for="cooking_time_hours">Время приготовления</label></div>
                        <input type="number" id="cooking_time_hours" name="cooking_time_hours" value="<?php echo floor($recipe['cooking_time_minutes'] / 60); ?>" min="0" required><span class = "recipe-form-value-title">:</span>
                        <input type="number" id="cooking_time_minutes" name="cooking_time_minutes" value="<?php echo $recipe['cooking_time_minutes'] % 60; ?>" min="0" required>
                    </div>
                
                
                    <div class="photo-file-download">
                        <div class ="photo-download">
                            <input type="file" id="main_image" class="file-download" name="main_image" accept="image/jpeg">
                            <?php
                                if (!empty($recipe['main_image_url'])) {
                                    $randomParam = '?' . rand();
                                    $photo_download_inner = "<img class='photo-download-img' src='../../uploads/recipeImages/{$recipe['main_image_url']}{$randomParam}' alt='Recipe Image'>";
                
                                }
                                else{
                                    $photo_download_inner =
                                    '<div class = "photo-download__icon">
                                        <div class = "phot-download__icon__item">+</div>
                                    </div>
                                    <div class = "photo-download__text">Загрузите ваше изображение</div>';
                                }
                                echo $photo_download_inner;
                            ?>
                
                        </div>
                    </div>
                    <script>
                        document.getElementById('main_image').addEventListener('change', handleImageUpload);
                        function handleImageUpload(event) {
                            const fileInput = event.target;
                            const photoDownload = document.querySelector('.photo-download');
                            if (fileInput.files.length > 0) {
                                const file = fileInput.files[0];
                                const reader = new FileReader();
                                reader.onload = function (e) {
                                    const imgElement = document.createElement('img');
                                    imgElement.src = e.target.result;
                                    imgElement.setAttribute("class", "photo-download-img");
                                    photoDownload.innerHTML = '';
                                    photoDownload.appendChild(fileInput);
                                    photoDownload.appendChild(imgElement);
                                };
                                reader.readAsDataURL(file);
                            } else {
                                photoDownload.innerHTML = '<div class="photo-download__icon"><div class="phot-download__icon__item">+</div></div><div class="photo-download__text">Загрузите ваше изображение</div>';
                            }
                        }
                    </script>
                    <h3 class = "recipe-form-value-title">Ингредиенты</h3>
                    <div id="ingredients" class = "recipe-form-ingridients">
                        <?php foreach ($ingredients as $ingredient) : ?>
                        <div class="recipe-form-ingredient">
                            <input type="text" id="ingredient_name_<?php echo $ingredient['ingredient_id']; ?>" name="ingredients_name[<?php echo $ingredient['ingredient_id']; ?>]" value="<?php echo $ingredient['name']; ?>" required>
                             
                            <input class = "recipe-form-ingredient-quantity" type="number" id="ingredient_quantity_<?php echo $ingredient['ingredient_id']; ?>" name="ingredients_quantity[<?php echo $ingredient['ingredient_id']; ?>]" value="<?php echo $ingredient['quantity_in_grams']; ?>" min="0" required>
                            <span>грамм</span>
                            <button type="button" onclick="removeIngredientRow(this)">Удалить</button>
                        </div>
                        <?php endforeach; ?>
                        <button type="button" onclick="addIngredientRow()">Добавить ингредиент</button>
                    </div>
                    <script>
                        function addIngredientRow() {
                            var container = document.getElementById('ingredients');
                            var newRow = document.createElement('div');
                            newRow.className = 'recipe-form-ingredient';
                            newRow.innerHTML = `
                
                                <input type="text" name="new_ingredients_name[]" required>
                
                                <input type="number" name="new_ingredients_quantity[]" min="0" required>
                                <button type="button" onclick="removeIngredientRow(this)">Удалить</button>`;
                            container.appendChild(newRow);
                        }
                        function removeIngredientRow(button) {
                            var row = button.parentNode;
                            row.parentNode.removeChild(row);
                        }
                        </script>
                            <div>
                                <h3 class = "recipe-form-value-title">Шаги приготовления</h3>
                                <div id="recipe_steps">
                                    <?php foreach ($steps as $step) : ?>
                                        <div class="recipe-form-step">
                                            <textarea id="step_description_<?php echo $step['step_id']; ?>" name="step_description[<?php echo $step['step_id']; ?>]" required><?php echo $step['description']; ?></textarea>
                                            <button type="button" onclick="removeStepRow(this)">Удалить</button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" onclick="addStepRow()">Добавить шаг</button>
                            </div>
                
                            <script>
                                function addStepRow() {
                                    var container = document.getElementById('recipe_steps');
                                    var newRow = document.createElement('div');
                                    newRow.className = 'recipe-form-step';
                                    newRow.innerHTML = `
                                        <textarea name="new_step_description[]" required></textarea>
                
                                        <button type="button" onclick="removeStepRow(this)">Удалить</button>
                                    `;
                                    container.appendChild(newRow);
                                }
                                function removeStepRow(button) {
                                    var row = button.parentNode;
                                    row.parentNode.removeChild(row);
                                }
                            </script>
                            <button type="submit" class = "add-recipe-btn">Изменить</button>
                        </form>
            </div>
        </div>
        <?php showAlert("recipe_change_message")?>
        
    </body>
</html>
