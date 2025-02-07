<?php
session_start();

// Список товаров
$productList = [
    1 => ['name' => 'Камера Sony Alpha', 'price' => 120000, 'image' => 'images/camera.jpg', 'description' => 'Профессиональная камера Sony Alpha с отличным качеством изображения.', 'sales' => 150],
    2 => ['name' => 'Микрофон Rode NT-USB', 'price' => 15000, 'image' => 'images/microphone.jpg', 'description' => 'Микрофон Rode NT-USB для записи подкастов и музыки.', 'sales' => 320],
    3 => ['name' => 'Осветительный комплект', 'price' => 8000, 'image' => 'images/lighting.jpg', 'description' => 'Комплект для освещения с регулируемыми лампами.', 'sales' => 200],
    4 => ['name' => 'Штатив Manfrotto', 'price' => 12000, 'image' => 'images/tripod.jpg', 'description' => 'Надежный штатив Manfrotto для профессионалов.', 'sales' => 180],
];

// Получаем ID товара из параметра запроса
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Проверяем, существует ли товар с таким ID
if (!isset($productList[$productId])) {
    // Если товар с таким ID не найден, перенаправляем на каталог товаров
    header('Location: catalog.php');
    exit;
}

$product = $productList[$productId];

// Обрабатываем добавление товара в корзину и отзыв
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['review'])) {
        // Добавляем новый отзыв в сессию
        $review = trim($_POST['review']);
        if (!isset($_SESSION['reviews'])) {
            $_SESSION['reviews'] = [];
        }
        $_SESSION['reviews'][] = [
            'review' => $review,
            'rating' => isset($_POST['rating']) ? (int)$_POST['rating'] : 0,
        ];
    }
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = $productId;
    header('Location: product-info.php?id=' . $productId);
    exit;
}

// Получаем все отзывы для этого товара
$reviews = isset($_SESSION['reviews']) ? $_SESSION['reviews'] : [];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О товаре: <?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Стили для страницы товара */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .product-info {
            padding: 20px;
            background-color: #fff;
            font-family: Arial, sans-serif;
        }

        .product-info h2 {
            font-size: 28px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .product-details {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .product-details img {
            max-width: 400px;
            max-height: 400px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .product-description {
            max-width: 500px;
            font-size: 16px;
            color: #555;
        }

        .product-description h3 {
            font-size: 20px;
            color: #333;
        }

        .product-description p {
            line-height: 1.6;
            margin: 10px 0;
        }

        .rating {
            font-size: 18px;
            color: #f39c12;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .back-to-catalog {
            text-align: center;
            margin-top: 30px;
        }

        .back-to-catalog .btn {
            font-size: 16px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-to-catalog .btn:hover {
            background-color: #218838;
        }

        /* Стили для отзыва */
        .reviews {
            margin-top: 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .reviews h3 {
            margin-bottom: 15px;
            font-size: 22px;
            color: #333;
        }

        .reviews form textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .reviews form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .reviews form button:hover {
            background-color: #0056b3;
        }

        .reviews .review {
            margin-top: 15px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .reviews .review p {
            font-size: 16px;
            color: #555;
        }

        .reviews .review .rating {
            font-size: 16px;
            color: #f39c12;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="product-info">
        <h2>О товаре: <?= htmlspecialchars($product['name']) ?></h2>
        <div class="product-details">
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            <div class="product-description">
                <h3>Описание:</h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p><strong>Цена: <?= htmlspecialchars($product['price']) ?> ₽</strong></p>
                <p><strong>Продано: <?= htmlspecialchars($product['sales']) ?> шт.</strong></p>
                <div class="rating">
                    <?php
                        $stars = round($product['sales'] / 100); // Простой расчет для отображения звезд
                        for ($i = 0; $i < 5; $i++) {
                            echo $i < $stars ? '★' : '☆';
                        }
                    ?>
                </div>
                <form method="POST" action="product-info.php?id=<?= $productId ?>">
                    <button type="submit" class="btn">Добавить в корзину</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Раздел с отзывами -->
    <section class="reviews">
        <h3>Отзывы</h3>
        <!-- Форма для добавления отзыва -->
        <form method="POST" action="product-info.php?id=<?= $productId ?>">
            <textarea name="review" placeholder="Напишите ваш отзыв..." required></textarea>
            <label for="rating">Оцените товар: </label>
            <select name="rating" required>
                <option value="1">1 звезда</option>
                <option value="2">2 звезды</option>
                <option value="3">3 звезды</option>
                <option value="4">4 звезды</option>
                <option value="5">5 звезд</option>
            </select>
            <button type="submit">Отправить отзыв</button>
        </form>

        <!-- Отображение отзывов -->
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <p><strong>Отзыв:</strong> <?= !empty($review['review']) ? htmlspecialchars($review['review'], ENT_QUOTES, 'UTF-8') : 'Отзыв пуст' ?></p>
                    <p class="rating">
                        Оценка: 
                        <?php
                            $rating = isset($review['rating']) && is_numeric($review['rating']) && $review['rating'] >= 1 && $review['rating'] <= 5 
                                ? (int)$review['rating'] 
                                : 0;
                            echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                        ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Нет отзывов для этого товара.</p>
        <?php endif; ?>
    </section>

    <section class="back-to-catalog">
        <a href="catalog.php" class="btn">Вернуться в каталог</a>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
