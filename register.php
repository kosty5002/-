<?php
session_start();

// Подключение к базе данных
include('db_connect.php');

// Инициализация переменных
$error = '';
$success = '';

// Обработка формы регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));

    // Проверка на пустые поля
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = "Все поля обязательны для заполнения.";
    } elseif (strlen($password) < 6) {
        $error = "Пароль должен быть не менее 6 символов.";
    } else {
        // Проверка, существует ли уже такой email
        $stmt = $conn->prepare("SELECT email FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Пользователь с таким email уже существует.";
        } else {
            // Хешируем пароль
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // SQL-запрос на добавление пользователя в базу данных
            $stmt = $conn->prepare("INSERT INTO Users (first_name, last_name, email, password, phone, address) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $first_name, $last_name, $email, $hashed_password, $phone, $address);

            if ($stmt->execute()) {
                $success = "Регистрация прошла успешно! Вы можете <a href='login.php'>войти</a>.";
            } else {
                $error = "Ошибка: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        main {
            max-width: 500px;
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

        .success {
            color: #28a745;
            margin: 15px 0;
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
    </style>
</head>
<body>

<main>
    <h2>Регистрация</h2>

    <!-- Вывод ошибок -->
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <!-- Вывод успеха -->
    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST" class="auth-form">
        <label for="first_name">Имя:</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Фамилия:</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="email">Электронная почта:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>

        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone">

        <label for="address">Адрес доставки:</label>
        <input type="text" id="address" name="address">

        <button type="submit">Зарегистрироваться</button>
    </form>

    <p>Уже есть аккаунт? <a href="login.php">Войдите</a></p>
    <p><a href="index.php">На главную</a></p>
</main>

</body>
</html>
