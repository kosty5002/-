<?php
session_start();

// Подключение к базе данных
include('db_connect.php');

// Инициализация переменных
$error = '';

// Имитация базы данных пользователей с хэшированными паролями
$users = [
    "admin" => password_hash("admin123", PASSWORD_DEFAULT), // Логин: admin, Пароль: admin123
    "user" => password_hash("user123", PASSWORD_DEFAULT)    // Логин: user, Пароль: user123
];

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Проверка логина и пароля из имитации базы
    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $username === 'admin' ? 'admin' : 'user';

        // Перенаправление на главную страницу после успешного входа
        header("Location: index.php");
        exit;
    } else {
        // Проверка в базе данных
        $sql = "SELECT * FROM Users WHERE email = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'user';  // Поставить роль 'user'
                header("Location: index.php"); // Перенаправление на главную страницу
                exit;
            } else {
                $error = "Неверный пароль.";
            }
        } else {
            $error = "Пользователь с таким логином не найден.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в личный кабинет</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        main {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            font-size: 1.8rem;
            color: #007bff;
        }

        .error {
            color: #dc3545;
            margin: 15px 0;
            font-weight: bold;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .auth-form input {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .auth-form button {
            padding: 10px 15px;
            font-size: 1rem;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .auth-form button:hover {
            background-color: #0056b3;
        }

        p {
            margin-top: 15px;
        }

        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        .profile-link {
            margin-top: 20px;
        }

        .profile-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .profile-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<main>
    <h2>Вход в личный кабинет</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['username'])): ?>
        <p>Вы уже вошли как <?= $_SESSION['username'] ?>. Перейдите в <a href="profile.php">Мой профиль</a>.</p>
    <?php else: ?>
        <form action="login.php" method="POST" class="auth-form">
            <label for="username">Логин (Email):</label>
            <input type="text" id="username" name="username" placeholder="Введите логин" required>

            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" placeholder="Введите пароль" required>

            <button type="submit">Войти</button>
        </form>
    <?php endif; ?>

    <p>Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
</main>
</body>
</html>
