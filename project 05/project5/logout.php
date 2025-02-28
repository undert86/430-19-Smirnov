<?php
session_start();
session_unset(); // Удаление всех переменных сессии
session_destroy(); // Завершение сессии
header("Location: index.php"); // Перенаправление на страницу входа
exit();
