<?php
session_start();

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Валидация данных
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['error'] = 'Пожалуйста, заполните все поля.';
        header('Location: contact.php');
        exit;
    }

    // Имитация отправки сообщения (можно заменить на отправку email)
    $_SESSION['success'] = "Спасибо, $name! Ваше сообщение отправлено.";
    header('Location: index.php'); // Переход на главную страницу после успешной отправки
    exit;
} else {
    // Если метод запроса не POST, перенаправляем на контактную страницу
    header('Location: contact.php');
    exit;
}
?>
