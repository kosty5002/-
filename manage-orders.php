<?php
session_start();

// Авторизация администратора
$adminPassword = '123';
$isAdmin = $_SESSION['isAdmin'] ?? false;

// Обработка авторизации
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if ($_POST['password'] === $adminPassword) {
        $_SESSION['isAdmin'] = true;
        $isAdmin = true;
    } else {
        $error = "Неверный пароль!";
    }
}

// Если пользователь не авторизован
if (!$isAdmin) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Вход администратора</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f9f9f9;
            }
            .login-form {
                padding: 20px;
                background: white;
                border-radius: 5px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                text-align: center;
            }
            .login-form input[type="password"],
            .login-form button {
                padding: 10px;
                margin: 10px 0;
                width: 100%;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
            }
            .login-form button {
                background-color: #007bff;
                color: white;
                border: none;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            .login-form button:hover {
                background-color: #0056b3;
            }
            .error {
                color: red;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <form class="login-form" method="POST">
            <h2>Вход администратора</h2>
            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <input type="password" name="password" placeholder="Введите пароль" required>
            <button type="submit" name="login">Войти</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// Пример данных заказов (обычно берутся из базы данных)
$orders = [
    ['id' => 1, 'user' => 'Иван Иванов', 'email' => 'ivanov@example.com', 'total' => 15000, 'status' => 'В обработке'],
    ['id' => 2, 'user' => 'Анна Смирнова', 'email' => 'anna@example.com', 'total' => 120000, 'status' => 'Доставлен'],
    ['id' => 3, 'user' => 'Петр Петров', 'email' => 'petrov@example.com', 'total' => 8000, 'status' => 'Отменен'],
];

// Фильтр и поиск
$filterStatus = $_GET['status'] ?? '';
$searchQuery = $_GET['search'] ?? '';

if ($filterStatus) {
    $orders = array_filter($orders, fn($order) => $order['status'] === $filterStatus);
}

if ($searchQuery) {
    $orders = array_filter($orders, fn($order) =>
        stripos($order['user'], $searchQuery) !== false || stripos($order['email'], $searchQuery) !== false
    );
}

// Обработка изменения статуса заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'] ?? null;
    $newStatus = $_POST['status'] ?? null;

    if ($orderId && $newStatus) {
        foreach ($orders as &$order) {
            if ($order['id'] == $orderId) {
                $order['status'] = $newStatus;
                $message = "Статус заказа №{$orderId} успешно обновлен на '{$newStatus}'.";
                break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление заказами</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }
        .filters {
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
        }
        .filters input, .filters select {
            padding: 10px;
            margin-right: 10px;
            font-size: 14px;
        }
        .filters button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .filters button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Управление заказами</h1>
        <nav>
            <a href="admin.php">Вернуться в панель администратора</a>
        </nav>
    </header>

    <main>
        <!-- Сообщение об успехе -->
        <?php if (!empty($message)): ?>
            <p class="success-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <!-- Фильтры и поиск -->
        <div class="filters">
            <form method="GET">
                <input type="text" name="search" placeholder="Поиск по имени или email" value="<?= htmlspecialchars($searchQuery) ?>">
                <select name="status">
                    <option value="">Все статусы</option>
                    <option value="В обработке" <?= $filterStatus === 'В обработке' ? 'selected' : '' ?>>В обработке</option>
                    <option value="Доставлен" <?= $filterStatus === 'Доставлен' ? 'selected' : '' ?>>Доставлен</option>
                    <option value="Отменен" <?= $filterStatus === 'Отменен' ? 'selected' : '' ?>>Отменен</option>
                </select>
                <button type="submit">Применить</button>
            </form>
        </div>

        <!-- Таблица заказов -->
        <table>
            <thead>
                <tr>
                    <th>№ заказа</th>
                    <th>Имя клиента</th>
                    <th>Email</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['user']) ?></td>
                        <td><?= htmlspecialchars($order['email']) ?></td>
                        <td><?= number_format($order['total'], 2, ',', ' ') ?> ₽</td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="status" required>
                                    <option value="В обработке" <?= $order['status'] === 'В обработке' ? 'selected' : '' ?>>В обработке</option>
                                    <option value="Доставлен" <?= $order['status'] === 'Доставлен' ? 'selected' : '' ?>>Доставлен</option>
                                    <option value="Отменен" <?= $order['status'] === 'Отменен' ? 'selected' : '' ?>>Отменен</option>
                                </select>
                                <button type="submit" name="update_status">Обновить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
