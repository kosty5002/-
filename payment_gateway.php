<?php
session_start();

// Check if the amount is passed through POST request
if (isset($_POST['amount'])) {
    $amount = $_POST['amount'];
} elseif (isset($_GET['amount'])) {
    $amount = $_GET['amount'];
} else {
    echo "<p>Некорректный запрос. Пожалуйста, попробуйте снова.</p>";
    exit;
}

// HTML styles
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
    .payment-options {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
    }
    .payment-option {
        background-color: #007bff;
        color: white;
        padding: 15px 30px;
        font-size: 18px;
        text-align: center;
        border-radius: 5px;
        cursor: pointer;
        width: 150px;
        transition: background-color 0.3s ease;
    }
    .payment-option:hover {
        background-color: #0056b3;
    }
    a {
        text-decoration: none;
        color: #fff;
    }
    a:hover {
        color: #ffcc00;
    }
    .back-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        font-size: 16px;
    }
    .form-input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .form-submit {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
    }
    .form-submit:hover {
        background-color: #218838;
    }
    .btn-back {
        display: inline-block;
        background-color: #f44336;
        color: white;
        padding: 12px 24px;
        text-align: center;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        margin-top: 20px;
    }
    .btn-back:hover {
        background-color: #d32f2f;
    }
</style>
";

// Display payment options
echo "
<div class='container'>
    <h2>Выберите способ оплаты</h2>
    <p>Сумма для оплаты: $amount руб.</p>

    <div class='payment-options'>
        <div class='payment-option'>
            <a href='payment_gateway.php?method=paypal&amount=$amount' class='btn'>PayPal</a>
        </div>

        <div class='payment-option'>
            <a href='payment_gateway.php?method=card&amount=$amount' class='btn'>Кредитная карта</a>
        </div>

        <div class='payment-option'>
            <a href='payment_gateway.php?method=cash&amount=$amount' class='btn'>Наличные</a>
        </div>
    </div>
    
    <div class='back-link'>
        <a href='index.php' class='btn-back'>Вернуться на главную страницу</a>
    </div>
</div>
";

// Handle payment method selection
if (isset($_GET['method']) && isset($_GET['amount'])) {
    $method = $_GET['method'];
    $amount = $_GET['amount'];

    switch ($method) {
        case 'paypal':
            // PayPal payment logic
            $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
            $business_email = "your-paypal-business-email@example.com";
            $return_url = "http://yourdomain.com/thank_you.php";
            $cancel_url = "http://yourdomain.com/cancel.php";

            echo "
            <div class='container'>
                <h2>Перейти к оплате через PayPal</h2>
                <form action='$paypal_url' method='post'>
                    <input type='hidden' name='cmd' value='_xclick'>
                    <input type='hidden' name='business' value='$business_email'>
                    <input type='hidden' name='item_name' value='Order'>
                    <input type='hidden' name='amount' value='$amount'>
                    <input type='hidden' name='currency_code' value='USD'>
                    <input type='hidden' name='return' value='$return_url'>
                    <input type='hidden' name='cancel_return' value='$cancel_url'>
                    <input type='submit' value='Перейти к оплате через PayPal' class='payment-option'>
                </form>
                <a href='index.php' class='btn-back'>Вернуться на главную страницу</a>
            </div>";
            break;

        case 'card':
            // Credit card payment form
            echo "
            <div class='container'>
                <h2>Оплата через кредитную карту</h2>
                <form method='POST' action='process_card_payment.php'>
                    <label for='card_number'>Номер карты</label>
                    <input type='text' id='card_number' name='card_number' class='form-input' required placeholder='XXXX-XXXX-XXXX-XXXX'>
                    
                    <label for='card_expiry'>Срок действия</label>
                    <input type='text' id='card_expiry' name='card_expiry' class='form-input' required placeholder='MM/YY'>
                    
                    <label for='card_cvc'>CVC</label>
                    <input type='text' id='card_cvc' name='card_cvc' class='form-input' required placeholder='XXX'>
                    
                    <input type='hidden' name='amount' value='$amount'>
                    
                    <input type='submit' value='Подтвердить оплату' class='form-submit'>
                </form>
                <a href='index.php' class='btn-back'>Вернуться на главную страницу</a>
            </div>";
            break;

        case 'cash':
            // Cash on delivery payment logic
            echo "
            <div class='container'>
                <h2>Оплата наличными при доставке</h2>
                <p>Вы выбрали оплату наличными на сумму $amount руб. Спасибо за ваш заказ!</p>
                <a href='index.php' class='btn-back'>Вернуться на главную страницу</a>
            </div>";
            break;

        default:
            echo "<p>Неверный способ оплаты. Пожалуйста, выберите другой метод.</p>";
            break;
    }
}
?>
