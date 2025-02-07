<?php
session_start();

$productList = [
    1 => ['name' => 'Камера Sony Alpha', 'price' => 120000, 'image' => 'images/camera.jpg', 'sales' => 150],
    2 => ['name' => 'Микрофон Rode NT-USB', 'price' => 15000, 'image' => 'images/microphone.jpg', 'sales' => 320],
    3 => ['name' => 'Осветительный комплект', 'price' => 8000, 'image' => 'images/lighting.jpg', 'sales' => 200],
    4 => ['name' => 'Штатив Manfrotto', 'price' => 12000, 'image' => 'images/tripod.jpg', 'sales' => 180],
];

// Поиск по названию товара
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Фильтрация товаров по поисковому запросу
$filteredProducts = array_filter($productList, function ($product) use ($searchQuery) {
    return empty($searchQuery) || stripos($product['name'], $searchQuery) !== false;
});

// Добавление товара в корзину
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = $productId;
}

$cartProducts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$totalPrice = 0;

foreach ($cartProducts as $productId) {
    if (isset($productList[$productId])) {
        $totalPrice += $productList[$productId]['price'];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог товаров</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="catalog">
        <h2>Каталог товаров</h2>

        <!-- Форма поиска -->
        <div class="search-form">
            <form method="GET" action="catalog.php">
                <input type="text" name="search" placeholder="Поиск товара..." value="<?= htmlspecialchars($searchQuery) ?>">
                <button type="submit">Поиск</button>
            </form>
        </div>

        <!-- Отображение товаров -->
        <div class="product-grid row">
            <?php foreach ($filteredProducts as $productId => $product): ?>
                <div class="product-card col">
                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="price"><?= htmlspecialchars($product['price']) ?> ₽</p>
                    <p class="sales">Продано: <?= htmlspecialchars($product['sales']) ?> шт.</p>
                    <div class="rating">
                        <?php
                            $stars = round($product['sales'] / 100);
                            for ($i = 0; $i < 5; $i++) {
                                echo $i < $stars ? '★' : '☆';
                            }
                        ?>
                    </div>
                    <form method="POST" action="catalog.php">
                        <input type="hidden" name="product_id" value="<?= $productId ?>">
                        <button type="submit" class="btn">Добавить в корзину</button>
                    </form>
                    <a href="product-info.php?id=<?= $productId ?>" class="btn">О товаре</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
