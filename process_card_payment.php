<?php
session_start();
header('Content-Type: application/json');

// Проверяем, что запрос отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Некорректный метод запроса."]);
    exit;
}

// Получаем и фильтруем входные данные
$cardNumber = trim(htmlspecialchars($_POST['card_number'] ?? ''));
$cardHolder = trim(htmlspecialchars($_POST['card_holder'] ?? ''));
$expiryDate = trim(htmlspecialchars($_POST['expiry_date'] ?? ''));
$cvv = trim(htmlspecialchars($_POST['cvv'] ?? ''));
$amount = trim(htmlspecialchars($_POST['amount'] ?? ''));
$orderId = $_POST['order_id'] ?? uniqid(); // Генерируем ID заказа, если он не передан

// Проверка корректности введенных данных
if (!preg_match('/^\d{16}$/', $cardNumber)) {
    echo json_encode(["status" => "error", "message" => "Некорректный номер карты."]);
    exit;
}

if (!preg_match('/^\d{3}$/', $cvv)) {
    echo json_encode(["status" => "error", "message" => "Некорректный CVV код."]);
    exit;
}

if (!preg_match('/^\d{2}\/\d{2}$/', $expiryDate)) {
    echo json_encode(["status" => "error", "message" => "Дата истечения должна быть в формате MM/YY."]);
    exit;
}

// Проверка срока действия карты
list($expMonth, $expYear) = explode('/', $expiryDate);
$expYear = "20" . $expYear; // Преобразуем 24 → 2024
if ($expYear < date('Y') || ($expYear == date('Y') && $expMonth < date('m'))) {
    echo json_encode(["status" => "error", "message" => "Срок действия карты истек."]);
    exit;
}

// Имитация успешного платежа
echo json_encode([
    "status" => "success",
    "message" => "Оплата прошла успешно!",
    "order_id" => $orderId,
    "amount" => $amount,
    "card_holder" => $cardHolder
]);
?>
