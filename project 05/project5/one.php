<?php
session_start();
// Предположим, что имя и фамилия пользователя уже сохранены в сессии, например:
if (!isset($_SESSION["user"])) {
    // Если пользователь не авторизован, можно сделать редирект на страницу входа
    header("Location: index.php");
    exit();
}


$filename = "schedule.csv"; // Файл с расписанием (CSV)

if (!file_exists($filename)) {
    die("Файл с расписанием не найден!");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Расписание</title>
    <link rel="stylesheet" href="one1.css?<?php echo time();?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel= "icon" href="Union.svg" type= "ico">
</head>
<body>
<header>
    <div class="container">
        <img src="Pulse_logo.svg" alt="Logo">
        <nav class="nav-links">
            <a href="#" class="link">Оценки</a>
            <div class="dropdown">
            <a href="#" class="link">Расписание</a>
            <div class="dropdown-menu">
                <a href="#">1 курс</a>
                <a href="#">2 курс</a>
                <a href="#">3 курс</a>
        </div>
    </div>
    <a href="#" class="link">Новости</a>
    <a href="#" class="link">О сайте</a>
</nav>

        <!-- Информация о пользователе -->
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION["user"]); ?></span>
            <a href="logout1.php" class="logout-btn">Выйти</a>
        </div>

        <!-- Бургер-меню для мобильных устройств -->
        <div class="burger-menu" onclick="toggleMenu()">
            <div class="burger-line"></div>
            <div class="burger-line"></div>
            <div class="burger-line"></div>
        </div>
        
    </div>
</header>


</body>
</html>
