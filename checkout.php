<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Основные стили для страницы */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2 {
            color: #333;
            text-align: center;
        }

        .checkout {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 16px;
            margin: 10px 0 5px;
            display: block;
            color: #333;
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="text"]:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }

        button.btn {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button.btn:hover {
            background-color: #0056b3;
        }

        form {
            padding: 10px;
        }

        form input[type="text"], form select {
            font-size: 16px;
            margin-top: 8px;
        }

        /* Стиль для подвала */
        footer {
            background-color: #333;
            color: white;
            padding: 20px 0;
            text-align: center;
            width: 100%;
            margin-top: auto; /* Подвинет подвал вниз */
        }

        /* Для мобильных устройств */
        @media (max-width: 768px) {
            .checkout {
                margin: 10px;
                padding: 15px;
            }

            button.btn {
                font-size: 16px;
            }

            .qr-code img {
                width: 150px;  /* уменьшение размера QR-кода на мобильных устройствах */
                height: 150px;
            }
        }

        .qr-code {
            text-align: center;
            margin-top: 20px;
        }

        .qr-code img {
            width: 250px;  /* увеличиваем размер QR-кода */
            height: 250px;  /* фиксированная высота */
            object-fit: contain;  /* изображение не будет обрезаться */
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="checkout">
        <h2>Оформление заказа</h2>
        <form action="process-order.php" method="POST" onsubmit="return validateForm()">
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" required pattern="[A-Za-zА-Яа-яЁё\s]+" title="Введите корректное имя" placeholder="Ваше имя">

            <label for="address">Адрес:</label>
            <input type="text" id="address" name="address" required title="Введите адрес доставки" placeholder="Адрес доставки">

            <label for="payment_method">Оплата:</label>
            <select name="payment_method" id="payment_method" required>
                <option value="card">Карта</option>
                <option value="sbp">СБП (Система быстрых платежей)</option>
            </select>

            <div id="card-details" style="display: none;">
                <label for="card_number">Номер карты:</label>
                <input type="text" id="card_number" name="card_number" pattern="\d{16}" title="Номер карты должен содержать 16 цифр" placeholder="Номер карты" maxlength="16">

                <label for="card_expiry">Срок действия:</label>
                <input type="text" id="card_expiry" name="card_expiry" pattern="\d{2}/\d{2}" title="Срок действия карты в формате MM/YY" placeholder="ММ/ГГ">
                
                <label for="card_cvc">CVC:</label>
                <input type="text" id="card_cvc" name="card_cvc" pattern="\d{3}" title="CVC должен содержать 3 цифры" placeholder="CVC">
            </div>

            <div class="qr-code">
                <h3>Для оплаты через СБП отсканируйте QR-код:</h3>
                <img src="images/kosty5002.png" alt="QR-код для СБП">
            </div>

            <button type="submit" class="btn">Оформить заказ</button>
        </form>
    </section>

    <?php include 'footer.php'; ?>

    <script>
        // Показать дополнительные поля для карты, если выбран метод оплаты "Карта"
        document.getElementById('payment_method').addEventListener('change', function() {
            var cardDetails = document.getElementById('card-details');
            if (this.value === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });

        // Валидация формы
        function validateForm() {
            const name = document.getElementById('name').value;
            const address = document.getElementById('address').value;
            const paymentMethod = document.getElementById('payment_method').value;
            const cardNumber = document.getElementById('card_number').value;

            if (!name || !address) {
                alert("Пожалуйста, заполните все поля.");
                return false;
            }

            // Дополнительная проверка для карты
            if (paymentMethod === 'card' && !cardNumber.match(/\d{16}/)) {
                alert("Пожалуйста, введите корректный номер карты.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
