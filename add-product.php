<?php
session_start();

// Инициализация данных о товарах (имитация базы данных)
$products = $_SESSION['products'] ?? [];

// Переменные для сообщений
$error = '';
$success = '';

// Обработка формы добавления товара
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $productName = htmlspecialchars(trim($_POST['product_name']));
        $productPrice = htmlspecialchars(trim($_POST['product_price']));
        $productImage = $_FILES['product_image'] ?? null;

        if (empty($productName) || empty($productPrice) || !$productImage) {
            $error = "Все поля обязательны для заполнения.";
        } elseif (!is_numeric($productPrice) || $productPrice <= 0) {
            $error = "Цена должна быть положительным числом.";
        } else {
            $uploadDir = 'images/';
            $uploadFile = $uploadDir . basename($productImage['name']);

            if (move_uploaded_file($productImage['tmp_name'], $uploadFile)) {
                $newProduct = [
                    'id' => count($products) + 1,
                    'name' => $productName,
                    'price' => $productPrice,
                    'image' => $uploadFile,
                ];
                $products[] = $newProduct;
                $_SESSION['products'] = $products;
                $success = "Товар успешно добавлен.";
            } else {
                $error = "Ошибка загрузки изображения.";
            }
        }
    }

    // Удаление товара
    if ($action === 'delete') {
        $productId = (int) $_POST['id'];
        $products = array_filter($products, fn($product) => $product['id'] !== $productId);
        $_SESSION['products'] = array_values($products);
        $success = "Товар успешно удален.";
    }

    // Редактирование товара
    if ($action === 'edit') {
        $productId = (int) $_POST['id'];
        $productName = htmlspecialchars(trim($_POST['product_name']));
        $productPrice = htmlspecialchars(trim($_POST['product_price']));
        $productImage = $_FILES['product_image'] ?? null;

        foreach ($products as &$product) {
            if ($product['id'] === $productId) {
                $product['name'] = $productName;
                $product['price'] = $productPrice;

                if ($productImage && $productImage['tmp_name']) {
                    $uploadDir = 'images/';
                    $uploadFile = $uploadDir . basename($productImage['name']);
                    if (move_uploaded_file($productImage['tmp_name'], $uploadFile)) {
                        $product['image'] = $uploadFile;
                    }
                }

                $success = "Товар успешно обновлен.";
                break;
            }
        }

        $_SESSION['products'] = $products;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление товарами</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Основные стили */
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        main {
            max-width: 900px;
            margin: 50px auto;
            padding: 25px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 1.8rem;
            color: #007bff;
        }

        .success, .error {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Таблицы */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.95rem;
        }

        table th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: center;
        }

        table td {
            background-color: #ffffff;
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        table img {
            max-width: 60px;
            border-radius: 8px;
        }

        .actions button {
            margin: 5px;
            padding: 8px 12px;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .actions button.delete {
            background-color: #dc3545;
        }

        .actions button.edit {
            background-color: #ffc107;
            color: #343a40;
        }

        /* Кнопка возврата */
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
<main>
<a href="admin.php" class="back-button">Вернуться в админ панель</a>
    <a href="catalog.php" class="back-button">Вернуться в каталог</a>
    <h2>Управление товарами</h2>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <!-- Форма добавления товара -->
    <form action="manage-products.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <label>Название товара:</label>
        <input type="text" name="product_name" required>
        <label>Цена:</label>
        <input type="text" name="product_price" required>
        <label>Изображение:</label>
        <input type="file" name="product_image" accept="image/*" required>
        <button type="submit">Добавить товар</button>
    </form>

    <!-- Список товаров -->
    <?php if (count($products) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Изображение</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= number_format($product['price'], 2, ',', ' ') ?> ₽</td>
                        <td><img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"></td>
                        <td class="actions">
                            <!-- Удаление -->
                            <form action="manage-products.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <button class="delete" type="submit">Удалить</button>
                            </form>
                            <!-- Редактирование -->
                            <form action="manage-products.php" method="POST" enctype="multipart/form-data" style="display:inline;">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <label>Название:</label>
                                <input type="text" name="product_name" value="<?= htmlspecialchars($product['name']) ?>" required>
                                <label>Цена:</label>
                                <input type="text" name="product_price" value="<?= htmlspecialchars($product['price']) ?>" required>
                                <label>Изображение:</label>
                                <input type="file" name="product_image" accept="image/*">
                                <button class="edit" type="submit">Сохранить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Товары отсутствуют.</p>
    <?php endif; ?>
</main>
</body>
</html>
