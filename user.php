<!-- <?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$user = [
    'username' => 'JohnDoe',
    'email' => 'johndoe@example.com',
    'join_date' => '2023-01-01'
];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="header">
    <h1>Профиль пользователя</h1>
    <nav>
        <a href="index.php">Главная</a>
        <a href="logout.php">Выход</a>
    </nav>
</div>

<div class="container">
    <h2>Информация о пользователе</h2>
    <div class="user-info">
        <p><strong>Имя пользователя:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Электронная почта:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Дата регистрации:</strong> <?php echo htmlspecialchars($user['join_date']); ?></p>
    </div>

    <h3>Изменить информацию</h3>
    <form action="update_profile.php" method="POST">
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="email">Электронная почта:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <button type="submit">Сохранить изменения</button>
    </form>
</div>

<div class="footer">
    <p>&copy; 2025. Все права защищены.</p>
</div>

</body>
</html> -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <section class="user-profile">
        <h2>Добро пожаловать, [Имя пользователя]</h2>
        <p>История заказов:</p>
        <ul>
            <li>Заказ #12345 на сумму 15000₽</li>
            <li>Заказ #12346 на сумму 120000₽</li>
        </ul>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
