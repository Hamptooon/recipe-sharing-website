<?php
    function insertRecipe($user_id, $title, $introduction, $cooking_time, $category_id, $mysqli, $status) {
        $sql = "INSERT INTO recipes (user_id, title, introduction,status, cooking_time_minutes, category_id) VALUES ($user_id, '$title', '$introduction', '$status', $cooking_time, $category_id)";
        
        if ($mysqli->query($sql) === FALSE) {
            return "Ошибка: " . $sql . "<br>" . $mysqli->error;
        }
    
        return $mysqli->insert_id;
    }
    function deleteRecipe($mysqli, $recipe_id){
        $delete_query = "DELETE FROM recipes WHERE recipe_id = $recipe_id";
        $mysqli->query($delete_query);
    }

    function getRecipe($mysqli, $recipe_id){
        $select_query = "SELECT * FROM recipes WHERE recipe_id = $recipe_id";
        $result = $mysqli->query($select_query);
        $recipe = $result->fetch_assoc();
        return $recipe;
    }
    function getRecipesbyCategoryId($mysqli, $category_id, $recipe_id){
        $select_recipes_query = "SELECT * FROM recipes WHERE category_id = $category_id and status = 'approved' and recipe_id != $recipe_id";
        $recipes = $mysqli->query($select_recipes_query);
        return $recipes;
    }


    function getCategoryById($mysqli, $category_id){
        $select_category_query = "SELECT category_name FROM recipe_categories WHERE category_id = $category_id";
        $result = $mysqli->query($select_category_query);
        $category_name = $result->fetch_assoc();
        return $category_name;
    }

    
    function approveRecipe($mysqli, $recipe_id){
        $recipe_approve_query = "UPDATE recipes SET status = 'approved' WHERE recipe_id = $recipe_id";
        $mysqli->query($recipe_approve_query);

    }
    function pendingRecipe($mysqli, $recipe_id){
        $recipe_approve_query = "UPDATE recipes SET status = 'pending' WHERE recipe_id = $recipe_id";
        $mysqli->query($recipe_approve_query);

    }
    function rejectRecipe($mysqli, $recipe_id){
        $recipe_reject_query = "UPDATE recipes SET status = 'rejected' WHERE recipe_id = $recipe_id";
        $mysqli->query($recipe_reject_query);

    }

    function insertModerateRecipeNote($mysqli, $recipe_id, $note){
        $insert_note_query = "INSERT INTO moderate_recipe_notes (recipe_id, note) VALUES ($recipe_id, '$note')";
        $mysqli->query($insert_note_query);
    }
    function insertIngredients($recipe_id, $names, $quantities, $mysqli) {
        for ($i = 0; $i < count($names); $i++) {
            $ingredient_name = $names[$i];
            $ingredient_quantity = (int)$quantities[$i];
            $sql = "INSERT INTO ingredients (recipe_id, name, quantity_in_grams) VALUES ($recipe_id, '$ingredient_name', $ingredient_quantity)";
            $mysqli->query($sql);
        }
    }
    
    function handleImageUpload($title, $recipe_id, $mysqli, $main_image) {
        $target_dir = "../../uploads/recipeImages/";
        $unique_id = uniqid(); // Генерация уникального идентификатора
        $target_file_load = $target_dir . $title . $recipe_id . '_' . $unique_id . ".jpeg";
        $target_file = $title . $recipe_id . '_' . $unique_id . ".jpeg";
    
        if ($main_image["error"] == 0 && $main_image["type"] === "image/jpeg") {
            move_uploaded_file($main_image["tmp_name"], $target_file_load);
            // Обновление пути до изображения в базе данных
            $sql = "UPDATE recipes SET main_image_url = '$target_file' WHERE recipe_id = $recipe_id";
            $mysqli->query($sql);
        }
        return $target_file_load;
    }
    
    
    function insertRecipeSteps($recipe_id, $descriptions, $mysqli) {
        for ($i = 0; $i < count($descriptions); $i++) {
            $step_description = $descriptions[$i];
            $sql = "INSERT INTO recipe_steps (recipe_id, step_order, description) VALUES ($recipe_id, $i+1, '$step_description')";
            $mysqli->query($sql);
        }
    }

    function updateIngredientsByRecipeId($mysqli, $recipe_id,  $ingredients_names, $ingredients_quantities){
        if (isset($_POST['ingredients_name']) && isset($_POST['ingredients_quantity'])) {
            $ingredients_names = $_POST['ingredients_name'];
            $ingredients_quantities = $_POST['ingredients_quantity'];
            $query = $mysqli->query("SELECT * FROM ingredients WHERE recipe_id = $recipe_id");
            if ($query) {
                $ingridients_count = $query->num_rows;
            } else {
                echo "Ошибка: " . $mysqli->error;
            }
            if(count($ingredients_names) == $ingridients_count) {
                foreach ($ingredients_names as $ingredient_id => $ingredient_name) {
                    $ingredient_quantity = (int)$ingredients_quantities[$ingredient_id];
                    $mysqli->query("UPDATE ingredients SET name = '$ingredient_name', quantity_in_grams = $ingredient_quantity WHERE ingredient_id = $ingredient_id");
                    }
            }
            else{
                $mysqli->query("DELETE FROM ingredients WHERE recipe_id = $recipe_id");
                foreach ($ingredients_names as $ingredient_id => $ingredient_name) {
                    $ingredient_name = $mysqli->real_escape_string($ingredient_name);
                    $ingredient_quantity = (int)$ingredients_quantities[$ingredient_id];
                    $sql = "INSERT INTO ingredients (recipe_id, name, quantity_in_grams) VALUES ($recipe_id, '$ingredient_name', $ingredient_quantity)";
                    $mysqli->query($sql);
                }
            }
        }
        else{
            $mysqli->query("DELETE FROM ingredients WHERE recipe_id = $recipe_id");
        }
    }
    function updateStepsByRecipeId($mysqli, $recipe_id,  $step_descriptions){
        $step_number = 0;
        if (isset($_POST['step_description'])) {
            $step_descriptions = $_POST['step_description'];
            $query = $mysqli->query("SELECT * FROM recipe_steps WHERE recipe_id = $recipe_id");
            if ($query) {
                $steps_count = $query->num_rows;
            } else {
                echo "Ошибка: " . $mysqli->error;
            }
            if(count($step_descriptions) == $steps_count){
                foreach ($step_descriptions as $step_id => $step_description) {
                    // Обновление записи шага в базе данных
                    $step_number++;
                    $mysqli->query("UPDATE recipe_steps SET description = '$step_description' WHERE step_id = $step_id");
                }
            }
            else{
                $mysqli->query("DELETE FROM recipe_steps WHERE recipe_id = $recipe_id");
                foreach ($step_descriptions as $step_id => $step_description) {
                    $step_number++;
                    $sql = "INSERT INTO recipe_steps (recipe_id, step_order, description) VALUES ($recipe_id, $step_number, '$step_description')";
                    $mysqli->query($sql);
                }
            }
        }
        else{
            $mysqli->query("DELETE FROM recipe_steps WHERE recipe_id = $recipe_id");
        }
        return $step_number;
    }
    function insertNewIngredients($mysqli, $recipe_id, $new_ingredients_names, $new_ingredients_quantities){
        if(!empty($new_ingredients_names) && !empty($new_ingredients_quantities))  {
            for ($i = 0; $i < count($new_ingredients_names); $i++) {
                $new_ingredient_name = $mysqli->real_escape_string($new_ingredients_names[$i]);
                $new_ingredient_quantity = (int)$new_ingredients_quantities[$i];
        
                $sql = "INSERT INTO ingredients (recipe_id, name, quantity_in_grams) VALUES ($recipe_id, '$new_ingredient_name', $new_ingredient_quantity)";
                $mysqli->query($sql);
            }
        
        }
    }
    function insertNewSteps($mysqli, $recipe_id,$new_steps_descriptions, $step_number){
        if(!empty($new_steps_descriptions)){
            for ($i = 0; $i < count($new_steps_descriptions); $i++) {
                $new_step_description = $mysqli->real_escape_string($new_steps_descriptions[$i]);
        
                $sql = "INSERT INTO recipe_steps (recipe_id, step_order, description) VALUES ($recipe_id, $step_number+1, '$new_step_description')";
                $mysqli->query($sql);
            }
        }
    }

    function updateRecipeImage($mysqli, $recipe_id, $main_image, $title){
        $result = $mysqli->query("SELECT title FROM recipes WHERE recipe_id = $recipe_id");
        $row = $result->fetch_assoc();
        if (!empty($main_image['name'])) {
            $result = $mysqli->query("SELECT main_image_url FROM recipes WHERE recipe_id = $recipe_id");
            $row = $result->fetch_assoc();
            $image_path = '../../uploads/recipeImages/' . $row['main_image_url'];
    
            if (file_exists($image_path)) {
                unlink($image_path);
            }
    
            return handleImageUpload($title, $recipe_id, $mysqli, $main_image);
        }
        return '';
    }

    function updateRecipe($mysqli, $recipe_id, $title, $introduction, $total_cooking_time, $category_id){
        $mysqli->query("UPDATE recipes SET title = '$title', introduction = '$introduction', cooking_time_minutes = $total_cooking_time, category_id = $category_id WHERE recipe_id = $recipe_id");
    }
    


    function checkCategoryExistence($mysqli, $title) {
        $checkCategoryQuery = "SELECT * FROM recipe_categories WHERE category_name = '$title'";
        $result = $mysqli->query($checkCategoryQuery);
        return $result->num_rows > 0;
    }
    
    function insertRecipeCategory($mysqli, $title) {
        $insertCategoryQuery = "INSERT INTO recipe_categories (category_name) VALUES ('$title')";
        return mysqli_query($mysqli, $insertCategoryQuery);
    }
    function updateRecipeCategory($mysqli, $title, $category_id){
        $updateCategoryQuery = "UPDATE recipe_categories SET category_name = '$title' where category_id = $category_id";
        return mysqli_query($mysqli, $updateCategoryQuery);
    }
    function deleteRecipeCategories($mysqli, $category_ids) {
        $success = true;
        
        foreach ($category_ids as $category_id) {
            $is_delete = $mysqli->query("DELETE FROM recipe_categories WHERE category_id = $category_id");
            
            if (!$is_delete) {
                $success = false;
                break;
            }
        }
    
        return $success;
    }

    function getRecipesCategories($mysqli){
        $select_categories_query = "SELECT * FROM  recipe_categories";
        $result = $mysqli->query($select_categories_query);
        return $result;

    }


    function updateUserInfo($mysqli, $username, $email, $user_id){
        
        $update_user_query = "UPDATE users SET username = '$username', email = '$email' WHERE user_id  = $user_id";
        $mysqli->query($update_user_query);

    }

    function getUserInfo($mysqli, $user_id){
        $select_user_query = "SELECT username, email FROM users WHERE user_id = $user_id";
        $result = $mysqli->query($select_user_query);
        $userInfo = $result->fetch_assoc();
        return $userInfo;
    }

    function updateUserPassword($mysqli, $password, $user_id){
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $update_password_query = "UPDATE users SET password_hash = '$password_hash' WHERE user_id = $user_id";
        $mysqli->query($update_password_query);
    }

    function getApprovedRecipes($mysqli)
    {
        $select_approved_recipes_query = "SELECT * FROM recipes WHERE status = 'approved'";
        $recipes = $mysqli->query($select_approved_recipes_query);
        return $recipes;

    }

    function getApprovedUserRecipes($mysqli, $user_id)
    {
        $select_approved_recipes_query = "SELECT * FROM recipes WHERE status = 'approved' AND user_id = $user_id";
        $recipes = $mysqli->query($select_approved_recipes_query);
        return $recipes;

    }


    function getPendingUserRecipes($mysqli, $user_id)
    {
        $select_approved_recipes_query = "SELECT * FROM recipes WHERE status = 'pending' AND user_id = $user_id";
        $recipes = $mysqli->query($select_approved_recipes_query);
        return $recipes;

    }
    function getRejectedUserRecipes($mysqli, $user_id)
    {
        $select_approved_recipes_query = "SELECT * FROM recipes WHERE status = 'rejected' AND user_id = $user_id";
        $recipes = $mysqli->query($select_approved_recipes_query);
        return $recipes;

    }
    function getFilteredRecipes($mysqli, $categoryFilter, $timeHoursStartFilter, $timeMinutesStartFilter, $timeHoursEndFilter, $timeMinutesEndFilter, $searchRecipeFilter) {
        $categoryFilterSql = '';
        if (!empty($categoryFilter)) {
            $categoryFilterSql = "AND category_id IN (" . implode(",", $categoryFilter) . ")";
        }
    
        $timeFilterSql = '';
        if ($timeHoursStartFilter > 0 || $timeMinutesStartFilter > 0) {
            $totalMinutes = $timeHoursStartFilter * 60 + $timeMinutesStartFilter;
            $timeFilterSql = "AND (cooking_time_minutes) >= $totalMinutes";
        }
        if ($timeHoursEndFilter > 0 || $timeMinutesEndFilter > 0) {
            $totalMinutes = $timeHoursEndFilter * 60 + $timeMinutesEndFilter;
            
            $timeFilterSql = $timeFilterSql . " AND (cooking_time_minutes) <= $totalMinutes";
        }
        $searchFilterSql = '';
        if (!empty($searchRecipeFilter)) {
            $searchFilterSql = "AND (title LIKE '%$searchRecipeFilter%' OR introduction LIKE '%$searchRecipeFilter%')";
        }
    
        $query = "SELECT * FROM recipes WHERE status = 'approved' $categoryFilterSql $timeFilterSql $searchFilterSql";
    

        $result = $mysqli->query($query);
    
        return $result;
    }


    
?>