<?php 
session_start();

// Товары в каталоге
$productList = [
    1 => ['name' => 'Камера Sony Alpha', 'price' => 120000, 'image' => 'camera.jpg', 'sales' => 150],
    2 => ['name' => 'Микрофон Rode NT-USB', 'price' => 15000, 'image' => 'microphone.jpg', 'sales' => 320],
    3 => ['name' => 'Осветительный комплект', 'price' => 8000, 'image' => 'lighting.jpg', 'sales' => 200],
    4 => ['name' => 'Штатив Manfrotto', 'price' => 12000, 'image' => 'tripod.jpg', 'sales' => 180],
];

// Инициализация корзины
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Добавление товара в корзину
if (isset($_GET['add'])) {
    $productId = intval($_GET['add']);
    if (isset($productList[$productId])) {
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + 1;
    }
    header("Location: cart.php");
    exit();
}

// Уменьшение количества товара
if (isset($_GET['decrease'])) {
    $productId = intval($_GET['decrease']);
    if (isset($_SESSION['cart'][$productId]) && $_SESSION['cart'][$productId] > 1) {
        $_SESSION['cart'][$productId]--;
    } else {
        unset($_SESSION['cart'][$productId]);
    }
    header("Location: cart.php");
    exit();
}

// Удаление товара из корзины
if (isset($_GET['remove'])) {
    $productId = intval($_GET['remove']);
    unset($_SESSION['cart'][$productId]);
    header("Location: cart.php");
    exit();
}

// Подсчет итоговой стоимости
$totalPrice = 0;
foreach ($_SESSION['cart'] as $productId => $quantity) {
    if (isset($productList[$productId])) {
        $totalPrice += $productList[$productId]['price'] * $quantity;
    }
}

// Применение скидки 5%, если сумма больше 50,000
$discount = 0;
if ($totalPrice > 50000) {
    $discount = $totalPrice * 0.05; // 5% скидка
    $totalPrice -= $discount; // Применяем скидку
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .cart {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .cart h2 {
            text-align: center;
            color: #333;
        }
        .cart ul {
            list-style: none;
            padding: 0;
        }
        .cart ul li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .cart .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .cart .product-info img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .cart .product-name {
            font-size: 18px;
            color: #333;
            flex-grow: 1;
        }
        .cart .product-price {
            font-size: 16px;
            color: #007bff;
        }
        .cart .quantity {
            display: flex;
            align-items: center;
        }
        .cart .quantity a {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            margin: 0 5px;
        }
        .cart .quantity span {
            font-size: 16px;
            font-weight: bold;
        }
        .cart .remove-btn {
            background-color: red;
            color: white;
            padding: 6px 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .cart .total-price {
            text-align: center;
            font-size: 20px;
            margin-top: 20px;
            font-weight: bold;
        }
        .cart .discount {
            text-align: center;
            font-size: 18px;
            color: #28a745;
        }
        .btn {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="cart">
        <h2>Корзина</h2>

        <?php if (empty($_SESSION['cart'])): ?>
            <p>Корзина пока пуста.</p>
            <a href="catalog.php" class="btn">Перейти в каталог</a>
        <?php else: ?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $productId => $quantity): ?>
                    <?php if (isset($productList[$productId])): ?>
                        <li>
                            <div class="product-info">
                                <img src="images/<?php echo $productList[$productId]['image']; ?>" alt="<?php echo $productList[$productId]['name']; ?>">
                                <div class="product-name"> <?php echo $productList[$productId]['name']; ?> </div>
                                <div class="product-price"> <?php echo $productList[$productId]['price']; ?> руб. </div>
                                <div class="quantity">
                                    <a href="cart.php?decrease=<?php echo $productId; ?>">-</a>
                                    <span><?php echo $quantity; ?></span>
                                    <a href="cart.php?add=<?php echo $productId; ?>">+</a>
                                </div>
                                <a href="cart.php?remove=<?php echo $productId; ?>" class="remove-btn">Удалить</a>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <?php if ($discount > 0): ?>
                <div class="discount">
                    Скидка 5% применена: -<?php echo number_format($discount, 2); ?> руб.
                </div>
            <?php endif; ?>

            <div class="total-price">
                Итоговая стоимость: <?php echo number_format($totalPrice, 2); ?> руб.
            </div>
            <a href="checkout.php" class="btn">Перейти к оформлению</a>
        <?php endif; ?>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
