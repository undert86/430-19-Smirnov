<?php
session_start();
require 'config.php'; // Подключение к базе данных

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $lastname = trim($_POST['lastname']);
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$login]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Пользователь с таким логином уже существует!'); window.location.href='registration.php';</script>";
            exit();
        }

        // Хеширование пароля
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Вставка нового пользователя
        $stmt = $pdo->prepare("INSERT INTO users (username, password, lastname, firstname, middlename, phone, email) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt->execute([$login, $hashed_password, $lastname, $firstname, $middlename, $phone, $email])) {
            // Получение ID зарегистрированного пользователя
            $user_id = $pdo->lastInsertId();

            // Сохранение данных пользователя в сессию
            $_SESSION["user_id"] = $user_id;
            $_SESSION["user"] = $firstname . ' ' . $lastname;

            echo "<script>alert('Регистрация прошла успешно!'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Ошибка регистрации! Попробуйте еще раз.'); window.location.href='registration.php';</script>";
        }
    } catch (PDOException $e) {
        die("Ошибка базы данных: " . $e->getMessage());
    }

    // Закрытие соединения
    $pdo = null;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Нарушениям.Нет</title>
    <link rel="stylesheet" href="main2.css?<?php echo time();?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="icon" href="Union.svg" type="image/x-icon"> 
</head>
<body>
  <header>
    <div class="container">
        <img src="yasha.svg">
        <nav>
        </nav>
    </div>
  </header>

  <div class="login-page">
    <div class="form">
      <form class="login-form" method="post" action="registration.php">
        <input type="text" name="login" id="login" placeholder="Логин" required/>
        <input type="password" name="password" id="password" placeholder="Пароль" required/>
        <input type="text" name="lastname" id="lastname" placeholder="Фамилия" required/>
        <input type="text" name="firstname" id="firstname" placeholder="Имя" required/>
        <input type="text" name="middlename" id="middlename" placeholder="Отчество"/>
        <input type="text" name="phone" id="phone" placeholder="Номер телефона" required/>
        <input type="email" name="email" id="email" placeholder="Email" required/>
        <button type="submit">Зарегистрироваться</button>
        <p class="message">Уже есть аккаунт? <a href="index.php">Войти</a></p>
      </form>
    </div>
  </div>    
</body>
</html>
