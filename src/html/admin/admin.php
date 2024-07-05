
<?php
    session_start();
    if (!isset($_SESSION["user_id"])){
        header('Location: ../login.php');
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/adminPageStyle.css">
    <title>Админ-панель</title>
</head>
<body>
<header>
        <div class="brand">
            <a href="../index.php" class="brand__title"><span class = "text__orange">Taste</span><span class = "text__yellow">Palette</span></a>
        </div>
        <!-- <div class="navigation">
            <div class="navigation__items">
                <a href="./recipeAddAdmin.php">Рецепты</a>

            </div>
        </div> -->
    </header>
   <script src = "../../js/hideHeader.js"></script>
    
</body>
</html>
