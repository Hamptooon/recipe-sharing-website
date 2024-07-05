<?php
   require '../../php/dbConnect.php'; 
   require './recipeAdminControl.php';
   require '../../php/utility/utilityFunctions.php'; 
   require '../../php/db/dbFunctions.php';
   $recipe_categories = $mysqli->query("SELECT * FROM recipe_categories");
   $recipes = getFilteredRecipes($mysqli, $categoryFilter, $timeHoursStartFilter, $timeMinutesStartFilter, $timeHoursEndFilter, $timeMinutesEndFilter, $searchRecipeFilter);

?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/recipeFormStyle.css">
        <link rel="stylesheet" href="../../css/alertStyle.css">
        

        <title>Добавление рецепта</title>
    </head>
    <body>


    
        <div class = "admin-recipes-container">
            <!-- <input type="text" id="searchInput" placeholder="Поиск рецепта..."><br> -->
            <button id="toggleFormButton">Добавить рецепт</button>
            <div id="recipeFormContainer" class="recipe-form" style="display: none;">
                <h2 class = "recipe-form-title">Добавление рецепта</h2>
            
                <!-- <form action="../../php/admin/recipeAddAdminProcess.php" method="post" enctype="multipart/form-data"> -->
                <form id="recipeForm" enctype="multipart/form-data">
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
            <div class="recipes">
                <?php while($recipe = $recipes->fetch_assoc()):?>
                    <div class="product-card" data-recipe-id="<?php echo $recipe['recipe_id'] ?>">
                        <img src="<?php echo '../../uploads/recipeImages/' . $recipe['main_image_url']?>" alt="Название товара" class="product-card__image">
                        <h3 class="product-card__title"><?php echo $recipe['title'] ?></h3>
                        <div class="product-card__description"><?php echo $recipe['introduction'] ?></div>
                        <!-- <a href="./recipeInfo.php?recipe_id=<?php echo $recipe['recipe_id'] ?>">
                            <button class="product-card__button">Посмотреть</button>
                        </a> -->
                        <div>
                            <button class="change-recipe-btn product-card__button">Изменить</button>
                            <button class="delete-recipe-btn product-card__button">Удалить</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <div class="response-log-container" id="responseLogContainer">
                <!-- <div class="log-entry log-entry-success"><div>asdaasdasda</div> <div>asdaasdasda</div> <div>asdaasdasda</div> <div class="time-date">20:12:02 12.04.2024</div></div> -->
            </div>
            <div id="recipeModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2 class="recipe-form-title">Изменение рецепта</h2>
                    <form id="changeRecipeForm" enctype="multipart/form-data">
                        <input type="hidden" name="recipe_id" id="modal_recipe_id">
                        <div class="recipe-name">
                            <label class="recipe-form-value-title" for="title">Название блюда:</label>
                            <input type="text" id="change_title" name="title" required>
                        </div>
                        <div class="recipe-category">
                            <label class="recipe-form-value-title" for="category">Категория:</label>
                            <select id="change_category" name="category" required>
                            </select>
                        </div>
                        <div>
                            <div><label class="recipe-form-value-title" for="introduction">Описание</label></div>
                            <textarea id="change_introduction" name="introduction" required></textarea>
                        </div>
                        <div class="recipe-form-time">
                            <div><label class="recipe-form-value-title" for="cooking_time_hours">Время приготовления</label></div>
                            <input type="number" id="change_cooking_time_hours" name="cooking_time_hours" min="0" required>
                            <span class="recipe-form-value-title">:</span>
                            <input type="number" id="change_cooking_time_minutes" name="cooking_time_minutes" min="0" required>
                        </div>
                        <div class="photo-file-download">
                            <div class="photo-download">
                                <input type="file" id="main_image1" class="file-download" name="main_image" accept="image/jpeg">
                            </div>
                        </div>
                        <h3 class="recipe-form-value-title">Ингредиенты</h3>
                        <div id="change_ingredients" class="recipe-form-ingredients">
            
                        </div>
                        <button type="button" onclick="addChangeIngredient()">Добавить ингредиент</button><br>
                        <h3 class="recipe-form-value-title">Шаги приготовления</h3>
                        <div id="change_recipe_steps">
            
                        </div>
                        <button type="button" onclick="addChangeStep()">Добавить шаг</button><br>
                        <button type="submit" class="add-recipe-btn">Изменить</button>
                    </form>
                </div>
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            
            // function fetchRecipes() {
            //                 $.ajax({
            //                     url: '../../php/admin/getAllRecipesData.php', 
            //                     type: 'GET',
            //                     dataType: 'json',
            //                     success: function(response) {
            //                         if (response.success) {
            //                             console.log('Recipes:', response.recipes);
            //                         } else {
            //                             console.error('Error:', response.message);
            //                         }
            //                     },
            //                     error: function(xhr, status, error) {
            //                         console.error('AJAX Error:', error);
            //                     }
            //                 });
            //             }

            // fetchRecipes();
            
            $('#searchInput').on('input', function() {
                    const searchText = $(this).val().toLowerCase();
                    $('.product-card').each(function() {
                        const title = $(this).find('.product-card__title').text().toLowerCase();
                        const description = $(this).find('.product-card__description').text().toLowerCase();
                        if (title.includes(searchText) || description.includes(searchText)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });


            
            $(document).ready(function() {
                $('#responseBlock').hide();

                $('#toggleFormButton').click(function() {
                    $('#recipeFormContainer').toggle();
                });

                $('#recipeForm').submit(function(event) {
                    event.preventDefault();
                    const formData = new FormData(this);
                    $.ajax({
                        url: '../../php/admin/recipeAddAdminProcess.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            const logEntry = createLogEntry(data, "add");
                            $('#responseLogContainer').prepend(logEntry);
                            if (data.success) {
                                console.log(data.main_image_url);
                                
                                const newRecipeCard = `
                                    <div class="product-card" data-recipe-id="${data.recipe_id}">
                                        <img src="../../uploads/recipeImages/${data.main_image_url}" alt="Название товара" class="product-card__image">
                                        
                                        <h3 class="product-card__title">${data.title}</h3>
                                        <div class="product-card__description">${data.introduction}</div>
                                        <div>
                                            <button class="change-recipe-btn product-card__button">Изменить</button>
                                            <button class="delete-recipe-btn product-card__button">Удалить</button>
                                        </div>
                                    </div>`;
                                $('.recipes').append(newRecipeCard);
                                $('#recipeFormContainer').hide();
                                resetForm();
                            }
                            $('#responseBlock').show();
                            setTimeout(function() {
                                $('#responseBlock').hide();
                            }, 3000);
                            $('#responseBlock').html(`<div class="alert__text-${data.success ? 'success' : 'error'}">${data.message}</div>`);
                        },
                        error: function(error) {
                            const logEntry = createLogEntry({
                                success: false,
                                message: 'Произошла ошибка при добавлении рецепта'
                            });
                            $('#responseLogContainer').prepend(logEntry);
                        }
                    });
                });

                function resetForm() {
                    $('#recipeForm')[0].reset();
                    $('#main_image').closest('.photo-download').html('<input type="file" id="main_image" class="file-download" name="main_image" accept="image/jpeg" required><div class="photo-download__icon"><div class="phot-download__icon__item">+</div></div><div class="photo-download__text">Загрузите ваше изображение</div>');
                    document.getElementById('main_image').addEventListener('change', handleImageUpload);
                }

                function handleImageUpload(event) {
                    const fileInput = event.target;
                    const photoDownload = event.target.closest('.photo-download');
                    console.log("-----");
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
                        photoDownload.innerHTML = '<input type="file" id="main_image" class="file-download" name="main_image" accept="image/jpeg" required><div class="photo-download__icon"><div class="phot-download__icon__item">+</div></div><div class="photo-download__text">Загрузите ваше изображение</div>';
                    }
                }

                let recipeDetailsInput1;
                $('#responseBlock').hide();

                function createLogEntry(data, type) {
                    const logEntry = document.createElement('div');
                    logEntry.classList.add('log-entry');
                    logEntry.classList.add(data.success ? 'log-entry-success' : 'log-entry-error');
                    let logEntryRes = `<div>${data.message}</div>`;

                    if (data.success) {
                        if (type === "add") {
                            
                            logEntryRes += `
                                <div><strong>Название:</strong> ${data.title}</div>
                                <div><strong>Категория:</strong> ${data.category}</div>
                                <div><strong>Время приготовления:</strong> ${data.cookingTimeHours} ч ${data.cookingTimeMinutes} мин</div>`;
                        } else if (type === "delete") {
                            logEntryRes += `
                            <div><strong>Название:</strong> ${data.title}</div>`;
                            // logEntryRes += `
                            // <button class="restore-recipe-btn" data-recipe-id="${data.recipe_id}">Восстановить</button>`;
                        }else if (type === "restore") {
                            logEntryRes += `
                            <div><strong>Название:</strong> ${data.title}</div>`;
                           
                        } else if (data.success && type === "edit") {
                            const changedFields = data.changed_fields;

                            if (changedFields.title) {
                                logEntryRes += `<div><strong>Название:</strong> <span class="line-through">${changedFields.title.old}</span> &#8594; ${changedFields.title.new}</div>`;
                            }
                            if (changedFields.introduction) {
                                logEntryRes += `<div><strong>Описание:</strong> <span class="line-through">${changedFields.introduction.old}</span> &#8594; ${changedFields.introduction.new}</div>`;
                            }
                            if (changedFields.category) {
                                logEntryRes += `<div><strong>Категория:</strong> <span class="line-through">${changedFields.category.old}</span> &#8594; ${changedFields.category.new}</div>`;
                            }
                            if (changedFields.cooking_time) {
                                logEntryRes += `<div><strong>Время приготовления:</strong> <span class="line-through">${Math.floor(changedFields.cooking_time.old / 60)} ч ${changedFields.cooking_time.old % 60} мин</span> &#8594; ${Math.floor(changedFields.cooking_time.new / 60)} ч ${changedFields.cooking_time.new % 60} мин</div>`;
                            }
                            // if (changedFields.main_image_url) {
                            //     logEntryRes += `<div><strong>Изображение:</strong> <span class="line-through">${changedFields.main_image_url.old}</span> &#8594; ${changedFields.main_image_url.new}</div>`;
                            // }
                        }
                    }

                    logEntryRes += `<div class="time-date">${data.timestamp}</div>`;
                    logEntry.innerHTML = logEntryRes;
                    return logEntry;
                }

                let recipesIdsForDelete = [];
                $(document).on('click', '.restore-recipe-btn', function(e) {
                    const recipeIdRestore = $(e.target).attr('data-recipe-id');
                    $.ajax({
                        url: '../../php/admin/getRecipesDetails.php',
                        type: 'GET',
                        data: { recipe_id: recipeIdRestore },
                        success: function(data) {
                            let response = data;
                            response.message = "Рецепт успешно восстановлен";
                            const logEntry = createLogEntry(response, "restore");
                            $('#responseLogContainer').prepend(logEntry);
                            
                            $(e.target).closest('.log-entry').hide();
                            if (response.success) {
                                recipesIdsForDelete.splice(recipesIdsForDelete.indexOf(recipeIdRestore));
                                console.log(recipesIdsForDelete);
                                const newRecipeCard = `
                                    <div class="product-card" data-recipe-id="${data.recipe_id}">
                                        <img src="../../uploads/recipeImages/${data.main_image_url}" alt="Название товара" class="product-card__image">
                                        
                                        <h3 class="product-card__title">${data.title}</h3>
                                        <div class="product-card__description">${data.introduction}</div>
                                        <div>
                                            <button class="change-recipe-btn product-card__button">Изменить</button>
                                            <button class="delete-recipe-btn product-card__button">Удалить</button>
                                        </div>
                                    </div>`;
                                $('.recipes').append(newRecipeCard);
                            }
                        },
                        error: function(error) {
                            const logEntry = createLogEntry({
                                success: false,
                                message: 'Произошла ошибка при удалении рецепта'
                            });
                            $('#responseLogContainer').prepend(logEntry);
                        }
                    });
                    // console.log(recipeDetails);
                    // const recipeData = JSON.parse(recipeDetails);
                    // console.log(recipeData);
                    // $.ajax({
                    //     url: '../../php/admin/restoreRecipe.php',
                    //     method: 'POST',
                    //     data: recipeData,
                    //     contentType: "application/json",
                    //     success: function(data) {
                    //         const response = JSON.parse(data);
                    //         const logEntry = createLogEntry(response, "restore");
                    //         $('#responseLogContainer').prepend(logEntry);

                    //         if (response.success) {
                    //             // Optionally, re-render the recipe card
                    //             location.reload();
                    //         }
                    //     },
                    //     error: function(xhr, status, error) {
                    //         const newRecipeCard = `
                    //                 <div class="product-card" data-recipe-id="${recipeData.recipe_id}">
                    //                     <img src="../../uploads/recipeImages/${recipeData.main_image_url}" alt="Название товара" class="product-card__image">
                                        
                    //                     <h3 class="product-card__title">${recipeData.title}</h3>
                    //                     <div class="product-card__description">${recipeData.introduction}</div>
                    //                     <div>
                    //                         <button class="change-recipe-btn product-card__button">Изменить</button>
                    //                         <button class="delete-recipe-btn product-card__button">Удалить</button>
                    //                     </div>
                    //                 </div>`;
                    //             $('.recipes').append(newRecipeCard);
                    //     }
                    // });
                });
                window.addEventListener('beforeunload', function (event) {
                    // Здесь можно выполнять необходимые действия, например, сохранение данных
                    
                    if(recipesIdsForDelete.length > 0){
                            console.log("adwawadw");
                            $.ajax({
                            url: '../../php/admin/recipeDeleteAdminProcess.php',
                            type: 'POST',
                            data: { recipe_ids: recipesIdsForDelete },
                            success: function(data) {
                                
                            },
                            error: function(error) {
                                const logEntry = createLogEntry({
                                    success: false,
                                    message: 'Произошла ошибка при удалении рецепта'
                                });
                                console.log('ошибка');
                                $('#responseLogContainer').prepend(logEntry);
                            }
                        });

                        // Чтобы показать пользователю диалоговое окно подтверждения, установите свойство returnValue у события
                        event.preventDefault(); // Этот метод требуется для некоторых браузеров
                        event.returnValue = ''; // Стандарт для большинства браузеров
                    }   
                    
                });
                document.getElementById('main_image').addEventListener('change', handleImageUpload);
              

                $(document).on('click', '.delete-recipe-btn', function() {
                    const recipeCard = $(this).closest('.product-card');
                    const recipeId = recipeCard.data('recipe-id');
                    recipesIdsForDelete.push(recipeId);
                    $.ajax({
                        url: '../../php/admin/getRecipesDetails.php',
                        type: 'GET',
                        data: { recipe_id: recipeId },
                        success: function(data) {
                            let response = data;
                            response.message = "Рецепт успешно удален";
                            const logEntry = createLogEntry(response, "delete");
                            $('#responseLogContainer').prepend(logEntry);
                            

                            if (response.success) {
                                recipeCard.remove();
                            }
                        },
                        error: function(error) {
                            const logEntry = createLogEntry({
                                success: false,
                                message: 'Произошла ошибка при удалении рецепта'
                            });
                            $('#responseLogContainer').prepend(logEntry);
                        }
                    });
                });


                $(document).on('click', '.change-recipe-btn', function() {
                    const recipeCard = $(this).closest('.product-card');
                    const recipeId = recipeCard.data('recipe-id');
                    const modal = $('#recipeModal');
                    const form = $('#changeRecipeForm');
                    clearForm();
                    $.ajax({
                        url: '../../php/admin/getRecipesDetails.php',
                        type: 'GET',
                        data: { recipe_id: recipeId },
                        success: function(data) {
                            if (data.success) {
                                $('#modal_recipe_id').val(data.recipe_id);
                                
                                $('#change_title').val(data.title);
                                $('#change_introduction').val(data.introduction);
                                $('#change_cooking_time_hours').val(Math.floor(data.cooking_time_minutes / 60));
                                $('#change_cooking_time_minutes').val(data.cooking_time_minutes % 60);
                                let photoDownload = document.getElementById("main_image1").closest(".photo-download");
                                photoDownload.innerHTML = `
                                    <input type="file" id="main_image1" class="file-download" name="main_image" accept="image/jpeg">
                                    <img class='photo-download-img' src='../../uploads/recipeImages/${data.main_image_url}' alt='Recipe Image'>
                                `;
                                document.getElementById('main_image1').addEventListener('change', handleImageUpload);

                                console.log(data.main_image_url);
                                // $('#change_category').val(data.category_id);
                                const categories = data.categories;
                                $('#change_category').empty();
                                categories.forEach(category => {
                                    const option = document.createElement('option');
                                    option.value = category.category_id;
                                    option.text = category.category_name;
                                    
                                    if (category.category_id === data.category_id) {
                                        option.selected = true;
                                    }
                                    
                                    $('#change_category').append(option);
                                });

                                const ingredientsHtml = data.ingredients.map(ingredient => `
                                    <div class="recipe-form-ingredient">
                                        <input type="text" name="ingredients_name[${ingredient.ingredient_id}]" value="${ingredient.name}" required>
                                        <input type="number" name="ingredients_quantity[${ingredient.ingredient_id}]" value="${ingredient.quantity_in_grams}" min="0" required>
                                        <span>грамм</span>
                                        <button type="button" onclick="removeIngredientRow(this)">Удалить</button>
                                    </div>
                                `).join('');
                                $('#change_ingredients').prepend(ingredientsHtml);

                                const stepsHtml = data.steps.map(step => `
                                    <div class="recipe-form-step">
                                        <textarea name="step_description[${step.step_id}]" required>${step.description}</textarea>
                                        <button type="button" onclick="removeStepRow(this)">Удалить</button>
                                    </div>
                                `).join('');
                                $('#change_recipe_steps').prepend(stepsHtml);

                                modal.show();
                            } else {
                                const logEntry = createLogEntry({
                                    success: false,
                                    message: 'Не удалось загрузить детали рецепта'
                                });
                                $('#responseLogContainer').prepend(logEntry);
                            }
                        },
                        error: function(error) {
                            const logEntry = createLogEntry({
                                success: false,
                                message: 'Произошла ошибка при загрузке деталей рецепта'
                            });
                            $('#responseLogContainer').prepend(logEntry);
                        }
                    });
                });

                $('.close').click(function() {
                    $('#recipeModal').hide();
                    clearForm();
                });

                $(window).click(function(event) {
                    if (event.target.id === 'recipeModal') {
                        $('#recipeModal').hide();
                        clearForm();
                    }
                });
                function clearForm() {
                    $('#changeRecipeForm')[0].reset();
                    $('#change_ingredients').empty();
                    $('#change_recipe_steps').empty();
                    $('#change_category').empty();
                }
                $('#changeRecipeForm').submit(function(event) {
                    event.preventDefault();
                    const formData = new FormData(this);

                    $.ajax({
                        url: '../../php/admin/recipeChangeAdminProcessV2.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            const logEntry = createLogEntry(data, "edit");
                            $('#responseLogContainer').prepend(logEntry);

                            if (data.success) {
                                $('#recipeModal').hide();
                                const recipeCard = $(`.product-card[data-recipe-id='${data.recipe_id}']`);
                                console.log(data.recipe_id);
                                if(data.main_image_url !== ''){
                                    recipeCard.find('.product-card__image').attr('src', `../upload.png`);
                                    recipeCard.find('.product-card__image').attr('src', `${data.main_image_url}`);
                                    console.log(data.main_image_url);
                                }
                                
                                recipeCard.find('.product-card__title').text(data.title);
                                recipeCard.find('.product-card__description').text(data.introduction);
                            }
                        },
                        error: function(error) {
                            const logEntry = createLogEntry({
                                success: false,
                                message: 'Произошла ошибка при изменении рецепта'
                            });
                            $('#responseLogContainer').prepend(logEntry);
                        }
                    });
                });
                
            });



        </script>
        <!-- <?php showAlert("recipe_add_message")?> -->

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
            function addChangeIngredient() {
                var container = document.getElementById("change_ingredients");
                var div = document.createElement("div");
                div.setAttribute("class", "recipe-form-ingredient")
                div.innerHTML = `<input type="text" name="new_ingredients_name[]"  required>
                                        <input type="number" name="new_ingredients_quantity[]" min="0" required>
                                        <span>грамм</span>
                                        <button type="button" onclick="removeIngredientRow(this)">Удалить</button>`;
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
            function addChangeStep() {
                var container = document.getElementById("change_recipe_steps");
                var div = document.createElement("div");
                div.setAttribute("class", "recipe-form-step")
                div.innerHTML = `<textarea name="new_step_description[]" required></textarea>
                                <button type="button" onclick="removeStepRow(this)">Удалить</button>`;
                container.appendChild(div);
            }
            function removeStepRow(button) {
                var row = button.parentNode;
                row.parentNode.removeChild(row);
            }
        </script>
        
    
    </body>
</html>

