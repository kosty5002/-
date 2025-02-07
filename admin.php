<!-- <?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>

<meta charset="UTF-8">
    <title>Административная панель</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Административная панель</h1>
    <p>Здесь вы можете управлять товарами и заказами.</p>
    <nav>
        <a href="catalog.php">Перейти в каталог товаров</a>
    </nav>
</body>
</html> -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Административная панель</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Основные стили */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        /* Секция панели администратора */
        .admin-panel {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .admin-panel h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #333;
        }

        .admin-panel .btn {
            display: inline-block;
            padding: 15px 25px;
            margin: 10px;
            font-size: 1em;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .admin-panel .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .admin-panel .btn:active {
            transform: scale(1);
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <section class="admin-panel">
        <h2>Управление товарами и заказами</h2>
        <a href="add-product.php" class="btn">Добавить товар</a>
        <a href="manage-orders.php" class="btn">Управление заказами</a>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
