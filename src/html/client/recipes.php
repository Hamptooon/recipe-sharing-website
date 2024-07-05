<?php
    session_start();
    require './header.php';
    require '../../php/dbConnect.php';
    require '../../php/db/dbFunctions.php';
    $recipe_categories = getRecipesCategories($mysqli);

    $categoryFilter = isset($_GET['categories_ids']) ? $_GET['categories_ids'] : array();
    $timeHoursStartFilter = isset($_GET['time_hours_start']) ? intval($_GET['time_hours_start']) : 0;
    $timeMinutesStartFilter = isset($_GET['time_min_start']) ? intval($_GET['time_min_start']) : 0;
    $timeHoursEndFilter = isset($_GET['time_hours_end']) ? intval($_GET['time_hours_end']) : 0;
    $timeMinutesEndFilter = isset($_GET['time_min_end']) ? intval($_GET['time_min_end']) : 0;
    $searchRecipeFilter = isset($_GET['search_recipe']) ? mysqli_real_escape_string($mysqli, $_GET['search_recipe']) : '';

    $recipes = getFilteredRecipes($mysqli, $categoryFilter, $timeHoursStartFilter, $timeMinutesStartFilter, $timeHoursEndFilter, $timeMinutesEndFilter, $searchRecipeFilter);

    
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/recipesStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <title>Рецепты</title>
</head>
<body>

<div class="filter-form">
        <form method="get" action="">
            <div class="multiselect">
                <div class="selectBox" onclick="showCheckboxes()">
                    <select>
                        <option>Выберите Категорию</option>
                    </select>
                    <div class="overSelect"></div>
                </div>
                <div id="checkboxes">
                    <?php
                        while ($row = $recipe_categories->fetch_assoc()) {
                            $checked = in_array($row['category_id'], $categoryFilter) ? 'checked' : '';
                            echo "<label><input type='checkbox' name='categories_ids[]' value='{$row['category_id']}' $checked>{$row['category_name']}</label>";
                        }
                    ?>
                </div>
            </div>

                
                    <div>
                        <div>Время приготовления</div>
                        <label for="time_hours">От</label>
                        <input type="number" placeholder ="Часы" id="time_hours" name="time_hours_start" value="<?php echo $timeHoursStartFilter; ?>">
                        <input type="number" placeholder ="Минуты" id="time_min" name="time_min_start" value="<?php echo $timeMinutesStartFilter; ?>">
                        <label for="time_hours">До</label>
                        <input type="number" placeholder ="Часы" id="time_hours" name="time_hours_end" value="<?php echo $timeHoursEndFilter; ?>">
                        <input type="number" placeholder ="Минуты" id="time_min" name="time_min_end" value="<?php echo $timeMinutesEndFilter; ?>">
                    </div>
                

                
                    <div>
                        <div><label for="search">Поиск по названию:</label></div>
                        <input type="text"  placeholder ="Название рецепта" id="search_recipe" name="search_recipe" value="<?php echo $searchRecipeFilter; ?>">
                    </div>
                

                <button type="submit">Применить фильтры</button>
        </form>
    </div>
    <script src = "../../js/filterFunctions.js"></script>

<div class="recipes">
        <?php while($recipe = $recipes->fetch_assoc()):?>
            <div class="product-card">
                <a href="./recipeInfo.php?recipe_id=<?php echo $recipe['recipe_id'] ?>">
                    <img src="<?php echo '../../uploads/recipeImages/' . $recipe['main_image_url']?>" alt="Название товара" class="product-card__image">
                </a>
                <h3 class="product-card__title"><?php echo $recipe['title'] ?></h3>
                <div class="product-card__description"><?php echo $recipe['introduction'] ?></div>
                <a href="./recipeInfo.php?recipe_id=<?php echo $recipe['recipe_id'] ?>">
                    <button class="product-card__button">Посмотреть</button>
                </a>
            </div>
        <?php endwhile; ?>
        
    </div>
</body>
</html>