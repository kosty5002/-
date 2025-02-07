<?php
// Start the session to access cart data
session_start();

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Корзина пуста. Пожалуйста, добавьте товары перед оформлением заказа.</p>";
    exit;
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : '';
    $payment_method = isset($_POST['payment_method']) ? htmlspecialchars(trim($_POST['payment_method'])) : '';
    
    // Validate form data
    if (empty($name) || empty($address) || empty($payment_method)) {
        echo "<p>Пожалуйста, заполните все обязательные поля.</p>";
        exit;
    }

    // Retrieve products in the cart
    $cart = $_SESSION['cart'];
    $productList = [
        1 => ['name' => 'Камера Sony Alpha', 'price' => 120000, 'image' => 'images/camera.jpg', 'sales' => 150],
        2 => ['name' => 'Микрофон Rode NT-USB', 'price' => 15000, 'image' => 'images/microphone.jpg', 'sales' => 320],
        3 => ['name' => 'Осветительный комплект', 'price' => 8000, 'image' => 'images/lighting.jpg', 'sales' => 200],
        4 => ['name' => 'Штатив Manfrotto', 'price' => 12000, 'image' => 'images/tripod.jpg', 'sales' => 180],
    ];

    // Calculate total price
    $totalPrice = 0;
    foreach ($cart as $productId) {
        if (isset($productList[$productId])) {
            $totalPrice += $productList[$productId]['price'];
        }
    }

    // Clear the cart after processing
    $_SESSION['cart'] = [];

    // Display order confirmation with styles
    echo "
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            font-size: 24px;
            text-align: center;
        }
        .order-info {
            font-size: 18px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .order-info p {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            color: #0056b3;
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
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>";

    // Display the order details
    echo "
    <div class='container'>
        <h2>Спасибо за ваш заказ, $name!</h2>
        <div class='order-info'>
            <p>Мы доставим ваш заказ по следующему адресу: $address.</p>
            <p>Способ оплаты: $payment_method.</p>
            <p>Общая стоимость заказа: $totalPrice руб.</p>
        </div>";

    // Display the "Pay" button or QR code depending on the payment method
    if ($payment_method == 'card' || $payment_method == 'paypal') {
        echo "<form action='payment_gateway.php' method='post'>
                <input type='hidden' name='amount' value='$totalPrice'>
                <button type='submit' class='btn'>Оплатить</button>
              </form>";
    } elseif ($payment_method == 'sbp') {
        // Display the QR code for SBP payment
        echo "<div class='qr-code'>
                <h3>Для оплаты через СБП отсканируйте QR-код:</h3>
                <img src='images/kosty5002.png' alt='QR-код для СБП'>
              </div>";
    }

    echo "<p><a href='index.php' class='btn'>Вернуться на главную страницу</a></p>";
    echo "</div>";
} else {
    echo "<p>Неправильный метод запроса.</p>";
}
?>
