<?php
    session_start();
    
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/mainPageStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <title>Главная страница</title>
</head>
<body>
    <header>
        <div class="brand">
            <a href="./index.php" class="brand__title"><span class = "text__orange">Taste</span><span class = "text__yellow">Palette</span></a>
        </div>
        <div class="menu-btn"></div>
        <div class="navigation">
            <div class="navigation__items">
                <a href="./index.php">Главная</a>
                <a href="./client/recipes.php">Рецепты</a>
                <?php
                    if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == TRUE)
                    {
                        echo '<a href="./admin/recipesChangeAdmin.php">Админ-панель</a>';
                    }
                ?>
                <div class="login-items">
                    <?php
                        if (!isset($_SESSION["user_id"])){
                            echo '<a href="./login.php" class = "login-btn">Войти</a>';
                        }
                    ?>
                    <a href="./client/profileChanging.php" class = "profile-btn"><img class = "profile-btn-img" src="../img/logo/icons/profileIcon.svg" alt=""></a>
                    <?php
                        if (isset($_SESSION["user_id"])){
                            echo '<a href="../php/signOutProcess.php" class = "signout-btn"><img class = "signout-btn-img" src="../img/logo/icons/signOutbtn.svg" alt=""></a>';
                        }
                    ?>
                    <div class  = "margin-header"></div>
                    
                </div>
            </div>
        </div>
    </header>
    <section class = "home">
        <video class="video-background" src="../video/food.mp4" autoplay muted loop></video>
        <div class="home__content">
            <h1 class = "home__content__title">Покажи свой рецепт <br>всему миру!</h1>
            <p>Добро пожаловать на <span class="brand__title"><span class="text__orange">Taste</span><span class="text__yellow">Palette</span></span> – место, где вы можете поделиться своими уникальными рецептами с миром! Независимо от вашего опыта в кулинарии, ваш вклад ценен. Загружайте любимые рецепты, делись секретами приготовления и вдохновляй других. От быстрых закусок до изысканных десертов, давайте создадим вместе кулинарную карту мира, полную вкуса и разнообразия!</p>
            <a href="./client/profileCreateRecipe.php">Создать рецепт</a>
        </div>
        <div class="media-icons">
            <a href="#"><i class = "fab fa-vk"></i></a>
            <a href="#"><i class = "fab fa-instagram"></i></a>
            <a href="#"><i class = "fab fa-telegram"></i></a>
        </div>
    </section>
    <script type="text/javascript">
        const menuBtn =document.querySelector(".menu-btn");
        const navigation =document.querySelector(".navigation");
        menuBtn.addEventListener("click", () =>{
            menuBtn.classList.toggle("active");
            navigation.classList.toggle("active");
        })

    </script>
</body>
</html>