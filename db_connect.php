<?php
// Параметры для подключения к базе данных
$servername = "localhost";  // или IP-адрес сервера
$username = "root";         // Имя пользователя для базы данных
$password = "";             // Пароль для базы данных (оставьте пустым, если его нет)
$dbname = "vlog_equipment_store";  // Имя базы данных

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Установить кодировку UTF-8
$conn->set_charset("utf8");

?>
