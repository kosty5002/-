<!-- <?php
session_start();
$productId = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Товар <?= $productId ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Товар <?= $productId ?></h1>
    <div class="image-gallery">
        <img src="product<?= $productId ?>.jpg" alt="Изображение товара">
    </div>

    <div class="product-details">
        <h2>Детальные характеристики</h2>
        <p>Бренд: Бренд <?= $productId ?></p>
        <p>Модель: Модель <?= $productId ?></p>
        <p>Функции: Функции товара</p>
    </div>

    <div class="reviews">
        <h2>Отзывы</h2>
        <p>Рейтинг: ★★★★☆</p>
        <p>Отзыв 1: Отличный товар!</p>
    </div>

    <form action="cart.php" method="POST">
        <input type="hidden" name="product_id" value="<?= $productId ?>">

        <button type="submit">Добавить в корзину</button>
    </form>

    <nav>
        <a href="catalog.php">Назад в каталог</a>
        <a href="cart.php">Перейти в корзину</a>
    </nav>
    <div class="header">
    <h1>Название сайта</h1>
    <nav>
        <a href="page1.html">Главная</a>
        <a href="page2.html">Товары</a>
        <a href="page3.html">Корзина</a>
    </nav>
</div>
</body>
</html> -->
<?php
$products = [
    1 => ['name' => 'Камера Sony Alpha', 'price' => 120000, 'description' => 'Идеальная камера для съемки.', 'image' => 'images/camera.jpg'],
    2 => ['name' => 'Микрофон Rode NT-USB', 'price' => 15000, 'description' => 'Качественный звук.', 'image' => 'images/microphone.jpg'],
    3 => ['name' => 'Осветительный комплект', 'price' => 8000, 'description' => 'Идеально для съемки.', 'image' => 'images/lighting.jpg'],
    4 => ['name' => 'Штатив Manfrotto', 'price' => 12000, 'description' => 'Удобство и надежность.', 'image' => 'images/tripod.jpg'],
];
$id = $_GET['id'] ?? 1;
$product = $products[$id] ?? null;
if (!$product) {
    die('Товар не найден.');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <section class="product-details">
        <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p><?= htmlspecialchars($product['description']) ?></p>
        <p class="price"><?= htmlspecialchars($product['price']) ?> ₽</p>
        <a href="cart.php?add=<?= $id ?>" class="btn">Добавить в корзину</a>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
