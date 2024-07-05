<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/headerStyle.css">
    </head>
    <body>
        <header>
            <div class="brand">
                <a href="../index.php" class="brand__title"><span class = "text__orange">Taste</span><span class = "text__yellow">Palette</span></a>
            </div>
            <div class="menu-btn"></div>
            <div class="navigation">
                <div class="navigation__items">
                    <a href="../index.php">Главная</a>
                    <a href="./recipes.php">Рецепты</a>
                    <?php
                        if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == TRUE)
                        {
                            echo '<a href="../admin/recipeAddAdmin.php">Админ-панель</a>';
                        }
                    ?>
                    <div class="login-items">
                        <?php
                            if (!isset($_SESSION["user_id"])){
                                echo '<a href="../login.php" class = "login-btn">Войти</a>';
                            }
                        ?>
                        <a href="./profileChanging.php" class = "profile-btn"><img class = "profile-btn-img" src="../../img/logo/icons/profileIcon.svg" alt=""></a>
                        <?php
                            if (isset($_SESSION["user_id"])){
                                echo '<a href="../../php/signOutProcess.php" class = "signout-btn"><img class = "signout-btn-img" src="../../img/logo/icons/signOutbtn.svg" alt=""></a>';
                            }
                        ?>
                        <div class = "margin-header"></div>
                    </div>
                </div>
            </div>
        </header>
        <script src = "../../js/hideHeader.js"></script>
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