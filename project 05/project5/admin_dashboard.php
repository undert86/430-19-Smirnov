<?php
session_start();
require 'config.php';

$query = "SELECT * FROM requests";
$stmt = $pdo->prepare($query);
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет администратора</title>
    <link rel="stylesheet" href="admin_dashboard.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" href="Union.svg" type="ico">
</head>
<body>
<header>
    <div class="container">
        <img src="yasha.svg" alt="Logo">
        <nav class="nav-links">
            <a href="#" class="link">Новости</a>
            <a href="#" class="link">О сайте</a>
        </nav>

        
    </div>
    <div class="user-info">
            <span>Администратор</span>
            <a href="logout.php" class="logout-btn">Выйти</a>
        </div>
</header>

<main>
    <div class="container1">
        <h1>Личный кабинет администратора</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Рег. номер</th>
                <th>Описание</th>
                <th>Статус</th>
                <th>Изменить статус</th>
            </tr>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo $request["id"]; ?></td>
                    <td><?php echo $request["number"]; ?></td>
                    <td><?php echo $request["description"]; ?></td>
                    <td><?php echo $request["status"]; ?></td>
                    <td>
                        <form action="update_status.php" method="POST">
                        <select name="status">
                        <option value="Новое" <?php if ($row['status'] == 'Новое') echo 'selected'; ?>>Новое</option>
                        <option value="В работе" <?php if ($row['status'] == 'В работе') echo 'selected'; ?>>В работе</option>
                        <option value="Закрыто" <?php if ($row['status'] == 'Закрыто') echo 'selected'; ?>>Закрыто</option>
                        </select>
                            <input type="hidden" name="request_id" value="<?php echo $request["id"]; ?>">
                            <button type="submit">Сохранить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Montserrat", sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        
        /* Стили для шапки */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 90px;
            background-color: #202020;
            border-bottom: 3px solid rgba(20, 20, 20, 0.4); 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 1000;
            justify-content: space-between; /* Располагаем элементы по краям */
        }
        
        .container {
            display: flex;
            align-items: center;
            justify-content: center; /* Центрируем содержимое контейнера */
            width: 100%;
            max-width: 1200px; /* Установил фиксированную ширину */
            margin: 0 auto;
            padding: 20px;
        }
        
        img {
            width: 140px;
            height: auto;
            position: absolute;
            left: 20px; /* Логотип на левом краю */
        }
        
        nav {
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: center;  /* Центрируем навигацию */
            flex-grow: 1;
        }
        
        nav a {
            font-family: "Montserrat", serif;
            text-decoration: none;
            color: #ffffff;
            font-size: 18px;
            font-weight: 500;
            padding: 0 15px;
            display: flex;
            align-items: center;
        }
        
        nav a::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: -5px; 
            width: 0; 
            height: 2px; 
            background-color: #ffffff; 
            transition: width 0.3s ease, left 0.3s ease;
            transform: translateX(-50%);
        }
        
        nav a:hover::after {
            width: 50%;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .user-info {
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            position: absolute;
            right: 20px;
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
            top: 130%;
        }
        
        .dropdown-menu a {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }
        
        .dropdown-menu a:hover {
            background-color: #444;
        }
        
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        
        /* Стили для таблицы обращений */
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
        
        /* Закрытие модального окна */
        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }
        
        /* Адаптивность */
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
                width: 120px;
                height: auto;
                position: relative;
            }
        
            nav a {
                font-size: 16px;
                padding: 5px 10px;
            }
        
            .user-info {
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            position: absolute;
            right: 20px;
            }
        
            .logout-btn {
                font-size: 12px;
                padding: 5px 8px;
            }
        
            table th, table td {
                padding: 10px;
            }
        
            .modal-content {
                width: 80%;
                margin: 15% auto;
            }
        }
        
        @media screen and (max-width: 480px) {
            nav a {
                font-size: 14px;
                padding: 5px 8px;
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
                width: 100px;
            }
        
            table th, table td {
                padding: 8px;
            }
        
            .modal-content {
                width: 90%;
                margin: 20% auto;
            }
        
            .close {
                font-size: 18px;
            }
        }
    
    </style>
</main>
</body>
</html>
