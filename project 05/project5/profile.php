<?php
session_start();
// Подключение к БД

// Проверка авторизации
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

$host = "localhost"; // Или IP-адрес сервера
$user = "root"; // Твой логин для БД
$password = ""; // Пароль для БД (оставь пустым, если нет)
$dbname = "user"; // Имя БД

$conn = new mysqli($host, $user, $password, $dbname);

// Проверка на успешное подключение
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Данные пользователя
$user_name = $_SESSION["user"];
$user_id = $_SESSION["user_id"];

// Запрос обращений пользователя
$stmt = $conn->prepare("SELECT id, number, description, status, date FROM requests WHERE user_id = ? ORDER BY date DESC");

if (!$stmt) {
    die("Ошибка запроса: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="main1.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" href="Union.svg" type="image/x-icon">
</head>
<body>
<header>
    <div class="container">
        <img src="yasha.svg" alt="Logo">
        <nav class="nav-links">
            <a href="#">Новости</a>
            <a href="#">О сайте</a>
        </nav>

        
    </div>
    <div class="user-info">
            <span><?php echo htmlspecialchars($user_name); ?></span>
            <a href="logout.php" class="logout-btn">Выйти</a>
        </div>
</header>

<main>
    <div class="container1">
        <h1>Добро пожаловать, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <button onclick="showModal()">Создать новое обращение</button>

        <h2>Ваши обращения</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Номер авто</th>
                    <th>Описание</th>
                    <th>Статус</th>
                    <th>Дата создания</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo htmlspecialchars($row["number"]); ?></td>
                        <td><?php echo htmlspecialchars($row["description"]); ?></td>
                        <td><?php echo htmlspecialchars($row["status"]); ?></td>
                        <td><?php echo date("d.m.Y H:i", strtotime($row["date"])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>У вас пока нет обращений.</p>
        <?php endif; ?>
        <?php $stmt->close(); ?>
           
        <!-- Модальное окно -->
        <div class="modal" id="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Создать новое обращение</h2>
                <form action="handle_request.php" method="POST">
                    <label for="number">Регистрационный номер авто:</label>
                    <input type="text" id="number" name="number" required><br>

                    <label for="description">Описание:</label>
                    <textarea id="description" name="description" required></textarea><br>

                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                    <button type="submit">Отправить</button>
                    <button type="button" onclick="closeModal()">Отмена</button>
                </form>
                <style>
                header {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 90px;
                background-color: #202020;
                border-bottom: 3px solid rgba(20, 20, 20, 0.4); /* Цвет фона, можно изменить */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                }

                .container {
                width: 100%;
                max-width: 1000px;
                max-height: 2000px;
                display: flex;
                align-items: center;
                justify-content: center;
                }

                img {
                position: absolute;
                right: 2350px;
                width: 140px;
                height: 1000px;
                top: -457px;

                }
                .burger-menu {
                display: none;
                flex-direction: column;
                gap: 5px;
                cursor: pointer;
                }

                .burger-line {
                width: 30px;
                height: 3px;
                background-color: #ffffff;
                border-radius: 5px;
                }
                nav {
                display: flex;
                gap: 10px;
                top: 20px;
                }

                nav a {
                position: relative;
                font-family: "Montserrat", serif;
                font-optical-sizing: auto;
                font-style: normal;
                text-decoration: none;
                color: #ffffff; /* Цвет текста */
                font-size: 18px;
                font-weight: 500;
                padding: 0px 15px;
                display: flex;
                align-items: center;
                }


                nav a::after {
                content: "";
                position: absolute;
                left: 50%;
                bottom: -5px; /* Увеличиваем значение, чтобы подчеркивание было ниже */
                width: 0; /* Начальная ширина 0 */
                height: 2px; /* Высота подчеркивания */
                background-color: #ffffff; /* Цвет подчеркивания */
                transition: width 0.3s ease, left 0.3s ease; /* Анимация ширины и позиции */
                transform: translateX(-50%); /* Центрируем подчеркивание */
                }

                nav a:hover::after {
                width: 50%; /* При наведении ширина становится 50% */
                left: 50%; /* Позиция слева остается 50% */
                transform: translateX(-50%); /* Центрируем подчеркивание */
                }
                .user-info {
                position: absolute;
                right: 20px;
                top: 50%;
                transform: translateY(-50%);
                color: #ffffff;
                font-family: "Montserrat", sans-serif;
                font-size: 18px;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 10px;
                }

                .logout-btn {
                background: red;
                color: white;
                padding: 5px 10px;
                border-radius: 5px;
                text-decoration: none;
                font-size: 14px;
                }
                .logout-btn:hover {
                background: darkred;
                }
                .dropdown {
                    position: relative;
                    display: inline-block;
                }
                .dropdown-menu {
                    display: none;
                    position: absolute;
                    background-color: #333;
                    min-width: 145px;
                    box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
                    z-index: 1;
                    border-radius: 5px;
                    text-decoration: none;
                    top: 130%;
                }
                .dropdown-menu a {
                    color: white;
                    padding: 10px 15px;
                    
                    display: block;
                }
                .dropdown-menu a:hover {
                    background-color: #444;
                }
                .dropdown:hover .dropdown-menu {
                    display: block;
                }
                .dropdown-menu a {
                text-decoration: none;
                }
                .dropdown-menu a::after {
                content: none;
                }
                @media screen and (max-width: 768px) {
                header {
                    height: auto;
                    padding: 10px 15px;
                }

                .container {
                    flex-direction: column;
                    align-items: flex-start;
                }

                img {
                    width: 120px; /* Уменьшаем логотип */
                    height: auto;
                    position: relative;
                    top: 0;
                    right: 0;
                }

                nav a {
                    font-size: 16px;
                    padding: 5px 10px;
                    text-decoration: none;
                }

                .user-info {
                    position: static;
                    transform: none;
                    margin-top: 15px;
                    font-size: 16px;
                    gap: 5px;
                }

                .logout-btn {
                    font-size: 12px;
                    padding: 5px 8px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 120px;
                }
                
                table th, table td {
                    padding: 12px;
                    text-align: center;
                    border: 1px solid #ddd;
                }
                
                table th {
                    background-color: #333;
                    color: white;
                }
                
                table tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
                
                table tr:hover {
                    background-color: #ddd;
                }
                
                select {
                    width: 100%;
                    padding: 5px;
                    margin-top: 5px;
                }
                
                button {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 15px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                }
                
                button:hover {
                    background-color: #45a049;
                }
                
                button:active {
                    background-color: #3e8e41;
                }
                
                /* Модальное окно */
                .modal {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 1000;
                }
                
                .modal-content {
                    background: white;
                    padding: 15px;
                    width: 40%; /* Уменьшил ширину */
                    margin: 10% auto;
                    border-radius: 10px;
                    position: relative;
                    max-height: 80vh;
                    overflow-y: auto;
                }
                @media screen and (max-width: 480px) {
                nav a {
                    font-size: 14px;
                    padding: 5px 8px;
                }
                nav.open {
                display: flex; /* Показываем меню при добавлении класса open */
                }
                .user-info {
                    font-size: 14px;
                    margin-top: 10px;
                }

                .logout-btn {
                    font-size: 12px;
                    padding: 4px 6px;
                }

                img {
                    width: 100px; /* Уменьшаем логотип ещё больше для очень маленьких экранов */
                }
                }
                }
            
    
        </style> 
            </div>
        </div>
    </div>
    
</main>

<script>
    function showModal() {
        document.getElementById('modal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }
</script>
</body>
</html>
