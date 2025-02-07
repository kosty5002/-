<?php
session_start();
$products = $_SESSION['products'] ?? [];
$productId = $_GET['id'] ?? null;
$product = null;

if ($productId) {
    foreach ($products as $item) {
        if ($item['id'] == $productId) {
            $product = $item;
            break;
        }
    }
}

if (!$product) {
    die('Товар не найден.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = htmlspecialchars($_POST['product_name']);
    $productPrice = htmlspecialchars($_POST['product_price']);
    $productImage = htmlspecialchars($_POST['product_image']);

    foreach ($products as &$item) {
        if ($item['id'] == $productId) {
            $item['name'] = $productName;
            $item['price'] = $productPrice;
            $item['image'] = $productImage;
            break;
        }
    }

    $_SESSION['products'] = $products;
    header('Location: add-product.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать товар</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<main>
    <h2>Редактировать товар</h2>
    <form method="POST">
        <label for="product_name">Название товара:</label>
        <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label for="product_price">Цена:</label>
        <input type="text" id="product_price" name="product_price" value="<?= htmlspecialchars($product['price']) ?>" required>

        <label for="product_image">Ссылка на изображение:</label>
        <input type="text" id="product_image" name="product_image" value="<?= htmlspecialchars($product['image']) ?>" required>

        <button type="submit">Сохранить</button>
    </form>
    <a href="add-product.php">Назад</a>
</main>
</body>
</html>
