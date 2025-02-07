<?php
session_start();

// Имитируем базу данных товаров (используем сессию)
$products = $_SESSION['products'] ?? [];

// Переменные для сообщений
$error = '';
$success = '';

// Фильтрация товаров
$filterName = $_GET['filter_name'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

$filteredProducts = array_filter($products, function ($product) use ($filterName, $minPrice, $maxPrice) {
    $nameMatch = empty($filterName) || stripos($product['name'], $filterName) !== false;
    $minMatch = empty($minPrice) || $product['price'] >= $minPrice;
    $maxMatch = empty($maxPrice) || $product['price'] <= $maxPrice;
    return $nameMatch && $minMatch && $maxMatch;
});

// Обработка добавления товара
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $productId = (int) $_POST['id'];
    $products = array_filter($products, fn($product) => $product['id'] !== $productId);
    $_SESSION['products'] = array_values($products);
    $success = "Товар успешно удален.";
}

// Редактирование товара
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
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
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление товарами</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        main {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        .success {
            color: green;
            text-align: center;
            font-weight: bold;
        }

        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }

        .filter-form {
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-form input,
        .filter-form button {
            padding: 10px;
            font-size: 16px;
        }

        .filter-form button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .filter-form button:hover {
            background-color: #0056b3;
        }

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

        table img {
            max-width: 50px;
            border-radius: 5px;
        }

        form.actions {
            display: inline-block;
        }

        form.actions button {
            margin: 5px;
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form.actions button.delete {
            background-color: #dc3545;
        }

        form.actions button:hover {
            background-color: #218838;
        }

        form.actions button.delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<main>
    
    <h2>Управление товарами</h2>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <!-- Фильтр товаров -->
    <form method="GET" class="filter-form">
        <input type="text" name="filter_name" placeholder="Название" value="<?= htmlspecialchars($filterName) ?>">
        <input type="number" name="min_price" placeholder="Мин. цена" value="<?= htmlspecialchars($minPrice) ?>">
        <input type="number" name="max_price" placeholder="Макс. цена" value="<?= htmlspecialchars($maxPrice) ?>">
        <button type="submit">Применить фильтр</button>
    </form>

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
    <h2>Список товаров</h2>
    <?php if (count($filteredProducts) > 0): ?>
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
                <?php foreach ($filteredProducts as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= number_format($product['price'], 2, ',', ' ') ?> ₽</td>
                        <td><img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"></td>
                        <td>
                            <!-- Удаление -->
                            <form action="manage-products.php" method="POST" class="actions">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <button class="delete" type="submit">Удалить</button>
                            </form>
                            <!-- Редактирование -->
                            <form action="manage-products.php" method="POST" enctype="multipart/form-data" class="actions">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <input type="text" name="product_name" value="<?= htmlspecialchars($product['name']) ?>" required>
                                <input type="text" name="product_price" value="<?= htmlspecialchars($product['price']) ?>" required>
                                <input type="file" name="product_image" accept="image/*">
                                <button type="submit">Сохранить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Товары не найдены по указанным критериям.</p>
    <?php endif; ?>
</main>
</body>
</html>
