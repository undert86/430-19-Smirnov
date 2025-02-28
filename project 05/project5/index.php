<?php
ob_start();
require 'config.php';  
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($login) || empty($password)) {
        echo "<script>alert('Пожалуйста, введите логин и пароль!'); window.location.href='index.php';</script>";
        exit();
    }

    if ($login === 'admin' && $password === 'admin_pass') {
        $_SESSION["user"] = "admin";  
        header("Location: admin_dashboard.php");
        die(); 
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $login]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user"] = $user["firstname"] . " " . $user["lastname"];
        header("Location: profile.php");
        die();
    } else {
        echo "<script>alert('Неверный логин или пароль!'); window.location.href='index.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Нарушениям.Нет</title>
    <link rel="stylesheet" href="main.css?<?php echo time(); ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" href="Union.svg" type="ico"> 
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
      <form class="login-form" method="post">
        <input type="text" name="username" id="username" placeholder="Логин"/>
        <input type="password" name="password" id="password" placeholder="Пароль"/>
        <button>Вход</button>
        <p class="message">Нет аккаунта? <a href="registration.php">Зарегистрироваться</a></p>
      </form>
    </div>
  </div>    

</body>
</html>
