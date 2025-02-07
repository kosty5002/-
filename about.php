<?php
session_start();

// Установка языка
$lang = $_SESSION['lang'] ?? 'ru';
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
}

// Перевод текста
$texts = [
    'ru' => [
        'about_title' => 'О нас',
        'about_intro' => 'Мы — магазин оборудования для видеоблогеров, предлагающий широкий ассортимент техники и аксессуаров.',
        'history_title' => 'Наша история',
        'history_desc' => 'Мы начали свою работу с небольшой команды, но за короткое время стали одним из ведущих поставщиков оборудования для создания контента.',
        'mission_title' => 'Наша миссия',
        'mission_desc' => 'Наша цель — помочь каждому видеоблогеру найти необходимое оборудование для создания качественного контента.',
        'values_title' => 'Наши ценности',
        'values_desc' => 'Мы верим в качество, надежность и удобство. Наши клиенты могут рассчитывать на честные цены и отличный сервис.',
    ],
    'en' => [
        'about_title' => 'About Us',
        'about_intro' => 'We are a store offering equipment for video bloggers with a wide range of technology and accessories.',
        'history_title' => 'Our History',
        'history_desc' => 'We started with a small team, but quickly became one of the leading suppliers of equipment for content creators.',
        'mission_title' => 'Our Mission',
        'mission_desc' => 'Our goal is to help every video blogger find the equipment needed to create high-quality content.',
        'values_title' => 'Our Values',
        'values_desc' => 'We believe in quality, reliability, and convenience. Our customers can count on fair prices and excellent service.',
    ],
];

$text = $texts[$lang];
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $text['about_title'] ?> - Магазин оборудования</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Стили для страницы "О нас" */
        .about-section {
            padding: 40px 20px;
            background-color: #f8f9fa;
            text-align: center;
        }

        .about-section h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .about-section p {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }

        .about-section h3 {
            font-size: 20px;
            margin-top: 40px;
            color: #007bff;
        }

        .about-section .history, .about-section .mission, .about-section .values {
            margin: 20px 0;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="about-section">
        <h2><?= $text['about_title'] ?></h2>
        <p><?= $text['about_intro'] ?></p>

        <div class="history">
            <h3><?= $text['history_title'] ?></h3>
            <p><?= $text['history_desc'] ?></p>
        </div>

        <div class="mission">
            <h3><?= $text['mission_title'] ?></h3>
            <p><?= $text['mission_desc'] ?></p>
        </div>

        <div class="values">
            <h3><?= $text['values_title'] ?></h3>
            <p><?= $text['values_desc'] ?></p>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
