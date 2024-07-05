<?php
    require '../../php/dbConnect.php';
    require './receiptCategoriesControlAdmin.php';
    require '../../php/utility/utilityFunctions.php';
    $recipe_categories = $mysqli->query("SELECT * FROM recipe_categories");
    session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../../css/adminDeleteStyle.css">
    <link rel="stylesheet" href="../../css/alertStyle.css">
    <link rel="stylesheet" href="../../css/categoryFormStyle.css">
    <title>Изменение/Удаление категорий</title>
</head>
<body>
    <div class="wrapper">
        <div class="category-form">
            <div class="toggleBtnBlock"><button id="toggleCategoryAddBtn">Добавить категорию</button></div>
        
            <div id="category-add-form" style="display:none;">
                <h2 class="category-form-title">Добавление категории</h2>
                <form id="addCategoryForm" enctype="multipart/form-data">
                    <div class="category-name">
                        <div><label class="category-form-value-title" for="title">Название категории</label></div>
                        <input type="text" placeholder="Название" id="title" name="title" required>
                    </div>
                    <button type="submit" class="category-btn">Добавить</button>
                </form>
            </div>
            <h2 class="category-form-title">Изменение/Удаление категорий</h2>
            <form id="deleteCategoryForm">
                <div class="delete-form" id="categoriesContainer">
                    <?php while ($row = $recipe_categories->fetch_assoc()): ?>
                        <div class="delete-elem">
                            <input type="checkbox" name="category_ids[]" value="<?= $row['category_id'] ?>"> <span class = "categoryName"><?= $row['category_name'] ?></span>
                            <a href="#" class="button edit-category-btn" data-category-id="<?= $row['category_id'] ?>" data-category-name="<?= $row['category_name'] ?>"><img src="../../img/logo/icons/changeIcon.png"></a>
                        </div>
                    <?php endwhile; ?>
                    <?php $mysqli->close(); ?>
                </div>
                <button type="submit" class="delete-btn">Удалить категории</button>
            </form>
        </div>
        <div class="response-log-container" id="responseLogContainer">
        </div>
        <div id="recipeModal" class="modal" style="display:none;">
            <div class="modal-content">
                <div>
                    <span class="close">&times;</span>
                    <h2 class="category-form-title">Изменение категории</h2>
                </div>
                <form id="changeCategoryForm">
                    <input type="hidden" id="editCategoryId" name="category_id">
                    <div>
                        <label class="category-form-value-title" for="editCategoryName">Название</label>
                    </div>
                    <input type="text" id="editCategoryName" name="title" required>
                    <button type="submit" class="category-btn">Изменить</button>
                </form>
            </div>
        </div>
    </div>
    <?php showAlert("categoryDeleteMessage")?>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#toggleCategoryAddBtn').click(function() {
            $('#category-add-form').toggle();
        });

        $('#addCategoryForm').submit(function(event) {
            event.preventDefault();
            $('#category-add-form').toggle();
            const formData = new FormData(this);

            $.ajax({
                url: '../../php/admin/recipeCategoryAddAdminProcess.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const logEntry = createLogEntry(response, "add");
                    $('#responseLogContainer').prepend(logEntry);
                    console.log(response.category_id);
                    console.log(response.category_name);
                    $('#categoriesContainer').append(`<div class="delete-elem">
                        <input type="checkbox" name="category_ids[]" value="${response.category_id}"> <span class = "categoryName">${response.category_name}</span>
                        <a href="#" class="button edit-category-btn" data-category-id="${response.category_id}" data-category-name="${response.category_name}">
                            <img src="../../img/logo/icons/changeIcon.png">
                        </a>
                    </div>`);
                    $('#addCategoryForm')[0].reset();
                },
                error: function(error) {
                    console.error('Error adding category:', error);
                }
            });
        });

        $('#deleteCategoryForm').submit(function(event) {
            event.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: '../../php/admin/recipeDeleteCategoryAdminProcess.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const logEntry = createLogEntry(response, "delete");
                    $('#responseLogContainer').prepend(logEntry);
                    $('input[name="category_ids[]"]:checked').each(function() {
                        $(this).closest('.delete-elem').remove();
                    });
                },
                error: function(error) {
                    console.error('Error deleting categories:', error);
                }
            });
        });

        $(document).on('click', '.edit-category-btn', function(event) {
            event.preventDefault();
            const categoryId = $(this).data('category-id');
            const categoryName = $(this).data('category-name');
            $('#editCategoryId').val(categoryId);
            $('#editCategoryName').val(categoryName);
            $('#recipeModal').show();
        });

        $('.close').click(function() {
            $('#recipeModal').hide();
        });

        $('#changeCategoryForm').submit(function(event) {
            event.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: '../../php/admin/recipeCategoryChangeAdminProcess.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const logEntry = createLogEntry(response, "edit");
                    $('#responseLogContainer').prepend(logEntry);
                
                    const categoryElem = $(`a.edit-category-btn[data-category-id="${response.category_id}"]`).closest('.delete-elem');
                    categoryElem.find('input').val(response.category_id).next(".categoryName").text(response.title);
                    $('#recipeModal').hide();
                },
                error: function(error) {
                    console.error('Error changing category:', error);
                }
            });
        });
        function createLogEntry(data, type) {
            const logEntry = document.createElement('div');
            logEntry.classList.add('log-entry');
            logEntry.classList.add(data.success ? 'log-entry-success' : 'log-entry-error');
            let logEntryRes = `<div>${data.message}</div>`;

            if (data.success) {
                if (type === "add") {
                    logEntryRes += `
                        <div><strong>Название:</strong> ${data.category_name}</div>`;
                } else if (type === "delete") {
                    logEntryRes += `
                        <div><strong>Удаленные категории:</strong></div>
                        <ul>`;
                    data.deleted_category_names.forEach(name => {
                        logEntryRes += `<li>${name}</li>`;
                    });
                    logEntryRes += `</ul>`;
                } else if (type === "edit") {
                    logEntryRes += `
                        <div><strong>Название:</strong> <span class="line-through">${data.oldTitle}</span> &#8594; ${data.title}</div>`;
                }
            }
            logEntryRes += `<div class="time-date">${data.timestamp}</div>`;
            logEntry.innerHTML = logEntryRes;
            return logEntry;
        }

    });

</script>
</html>
