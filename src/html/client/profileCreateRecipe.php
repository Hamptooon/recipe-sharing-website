<?php
    session_start();
    require './profileControlPanel.php';
    require '../../php/db/dbFunctions.php';
    require '../../php/dbConnect.php';
    require '../../php/utility/utilityFunctions.php';
    $recipe_categories = getRecipesCategories($mysqli);
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
            <div class="recipe-form">
            <h2 class = "recipe-form-title">Добавление рецепта</h2>
            
            <form action="../../php/client/recipeAddProcess.php" method="post" enctype="multipart/form-data">
                <div class="recipe-name">
                    <label class = "recipe-form-value-title" for="title">Название блюда</label><br>
                    <input type="text" placeholder="Название" id="title" name="title" required>
                </div>
                
                <div class="recipe-category">
                    <label class = "recipe-form-value-title" for="category">Категория</label><br>
                    <select id="category" name="category" required>
                        <?php
                            
                            while ($row = $recipe_categories->fetch_assoc()) {
                                echo "<option value=\"{$row['category_id']}\">{$row['category_name']}</option>";
                            }
                            $mysqli->close();
                        ?>
                    </select><br>
                </div><br>

                <label class = "recipe-form-value-title" for="introduction">Описание</label><br>
                <textarea id="introduction" placeholder="Описание" name="introduction" required></textarea><br>
            
                <div class="recipe-form-time">
                    <label class = "recipe-form-value-title" for="cooking_time_hours">Время приготовления</label><br>
                    <input type="number" id="cooking_time_hours" placeholder="Часы" name="cooking_time_hours" min="0" required><span class = "recipe-form-value-title">:</span>
                    <input type="number" id="cooking_time_minutes" placeholder="Минуты" name="cooking_time_minutes" min="0" required><br>
                </div>
            

                <h3 class = "recipe-form-value-title">Ингредиенты:</h3>
                <div id="ingredients">
                    <div class = "recipe-form-ingredient">
                        <input type="text" name="ingredients_name[]" placeholder="Название ингредиента" required>
                        <input type="number" name="ingredients_quantity[]" placeholder="Количество (г)" required>
                        <button type="button" onclick="removeIngredientRow(this)">Удалить</button>
                    </div>
                </div>
                <button type="button" onclick="addIngredient()">Добавить ингредиент</button><br>
                <div class="photo-file-download">
                    <div class ="photo-download">
                        <input type="file" id="main_image" class="file-download" name="main_image" accept="image/jpeg" required>
                        <div class = "photo-download__icon">
                            <div class = "phot-download__icon__item">+</div>
                        </div>
                        <div class = "photo-download__text">Загрузите ваше изображение</div>
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

                        
                <h3 class = "recipe-form-value-title">Шаги приготовления:</h3>
                <div id="recipe_steps">
                    <div class = "recipe-form-step">
                        <textarea name="step_description[]" placeholder="Описание шага" required></textarea>
                        <button type="button" onclick="removeStepRow(this)">Удалить</button>
                    </div>
                </div>
                <button type="button" onclick="addStep()">Добавить шаг</button><br>
            
                <button type="submit" class = "add-recipe-btn">Создать</button>
            </form>
        </div>
        


        <script>
            function addIngredient() {
                var container = document.getElementById("ingredients");
                var div = document.createElement("div");
                div.setAttribute("class", "recipe-form-ingredient")
                div.innerHTML = '<input type="text" name="ingredients_name[]" placeholder="Название ингредиента" required>' +
                    '<input type="number" name="ingredients_quantity[]" placeholder="Количество (г)" required>'+
                    '<button type="button" onclick="removeIngredientRow(this)">Удалить</button>';
                container.appendChild(div);
            }
            function removeIngredientRow(button) {
                var row = button.parentNode;
                row.parentNode.removeChild(row);
            }
            function addStep() {
                var container = document.getElementById("recipe_steps");
                var div = document.createElement("div");
                div.setAttribute("class", "recipe-form-step")
                div.innerHTML = '<textarea name="step_description[]" placeholder="Описание шага" required></textarea>'+
                    '<button type="button" onclick="removeStepRow(this)">Удалить</button>';
                container.appendChild(div);
            }
            function removeStepRow(button) {
                var row = button.parentNode;
                row.parentNode.removeChild(row);
            }
        </script>
        </div>
        <?php showAlert("recipe_add_message")?>
</body>
</html>