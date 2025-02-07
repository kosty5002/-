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
        'welcome' => 'Добро пожаловать!',
        'best_equipment' => 'Лучшее оборудование для видеоблогеров.',
        'to_catalog' => 'Перейти в каталог',
        'new_arrivals' => 'Новые поступления',
    ],
    'en' => [
        'welcome' => 'Welcome!',
        'best_equipment' => 'The best equipment for video bloggers.',
        'to_catalog' => 'Go to catalog',
        'new_arrivals' => 'New Arrivals',
    ],
];
$text = $texts[$lang];
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная - Магазин оборудования</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Шапка -->
    <header class="header">
        <div class="logo">
            <a href="index.php">Магазин оборудования</a>
        </div>
        <nav class="navbar">
            <a href="index.php"><?= $text['to_catalog'] ?></a>
            <a href="about.php">О нас</a>
            <a href="contact.php">Контакты</a>
            <a href="?lang=ru">RU</a> | <a href="?lang=en">EN</a>
        </nav>
        <div class="header-actions">
            <button onclick="toggleAccessibility('large-font')">A+</button>
            <button onclick="toggleAccessibility('high-contrast')">Контраст</button>
        </div>
    </header>

    <!-- Слайдер -->
    <section class="slider">
        <div class="slider-container">
            <div class="slide"><img src="images/camera.jpg" alt="Слайд 1"></div>
            <div class="slide"><img src="images/lighting.jpg" alt="Слайд 2"></div>
            <div class="slide"><img src="images/microphone.jpg" alt="Слайд 3"></div>
            <div class="slide"><img src="images/tripod.jpg" alt="Слайд 4"></div>
        </div>
        <!-- Кнопки управления -->
        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>
    </section>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;
        const sliderContainer = document.querySelector('.slider-container');

        function changeSlide(next = true) {
            currentSlide = next ? (currentSlide + 1) % totalSlides : (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }

        function updateSlider() {
            const offset = -currentSlide * 100;
            sliderContainer.style.transform = `translateX(${offset}%)`;
        }

        // Автоматическое перелистывание каждые 3 секунды
        let sliderInterval = setInterval(() => changeSlide(true), 3000);

        // Остановить автоперелистывание при наведении
        document.querySelector('.slider').addEventListener('mouseover', () => clearInterval(sliderInterval));
        document.querySelector('.slider').addEventListener('mouseout', () => sliderInterval = setInterval(() => changeSlide(true), 3000));

        // Кнопки управления слайдером
        document.querySelector('.next').addEventListener('click', () => changeSlide(true));
        document.querySelector('.prev').addEventListener('click', () => changeSlide(false));
    </script>

    <!-- Контент -->
    <section class="banner">
        <h2><?= $text['welcome'] ?></h2>
        <p><?= $text['best_equipment'] ?></p>
        <a href="catalog.php" class="btn"><?= $text['to_catalog'] ?></a>
    </section>

    <section class="why-choose-us">
        <h2>Почему выбирают нас?</h2>
        <div class="features">
            <div class="feature-item">
                <div class="icon">📦</div>
                <h3>Широкий ассортимент</h3>
                <p>У нас вы найдете оборудование для любых задач: от видеокамер до аксессуаров.</p>
            </div>
            <div class="feature-item">
                <div class="icon">🏆</div>
                <h3>Высокое качество</h3>
                <p>Мы предлагаем только проверенные бренды с гарантией качества.</p>
            </div>
            <div class="feature-item">
                <div class="icon">🚚</div>
                <h3>Быстрая доставка</h3>
                <p>Мы обеспечиваем быструю доставку по всей стране.</p>
            </div>
        </div>
    </section>

    <section class="latest-products">
        <h3><?= $text['new_arrivals'] ?></h3>
        <div class="product-grid">
            <div class="product-card">
                <img src="images/camera.jpg" alt="Камера Sony Alpha">
                <h4>Камера Sony Alpha</h4>
                <p>Цена: 120000 ₽</p>
                <a href="product.php?id=1" class="btn">Подробнее</a>
            </div>
            <div class="product-card">
                <img src="images/microphone.jpg" alt="Микрофон Rode NT-USB">
                <h4>Микрофон Rode NT-USB</h4>
                <p>Цена: 15000 ₽</p>
                <a href="product.php?id=2" class="btn">Подробнее</a>
            </div>
            <div class="product-card">
                <img src="images/lighting.jpg" alt="Осветительный комплект">
                <h4>Осветительный комплект</h4>
                <p>Цена: 8000 ₽</p>
                <a href="product.php?id=3" class="btn">Подробнее</a>
            </div>
        </div>
    </section>

    <!-- Подвал -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-left">
                <h4>Контакты</h4>
                <p>Телефон: +7 (000) 000-00-00</p>
                <p>Email: support@example.com</p>
                <p>Адрес: ул. Костычева, 1, г. Иркутск, Россия</p>
            </div>
            <div class="footer-center">
                <h4>Полезные ссылки</h4>
                <ul>
                    <li><a href="about.php">О нас</a></li>
                    <li><a href="privacy.php">Политика конфиденциальности</a></li>
                    <li><a href="terms.php">Условия использования</a></li>
                    <li><a href="contact.php">Контакты</a></li>
                </ul>
            </div>
            <div class="footer-right">
                <h4>Следите за нами</h4>
                <div class="social-links">
                    <a href="https://t.me/kosty50024" target="_blank">Наш телеграмм</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Магазин оборудования. Все права защищены.</p>
        </div>
    </footer>

    <script>
        // Переключение шрифтов и контраста
        function toggleAccessibility(option) {
            document.body.classList.toggle(option);
        }
    </script>

    <style>
        /* Общие стили */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo a {
            font-size: 1.8em;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .header-actions button {
            background-color: transparent;
            border: 2px solid white;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 10px;
        }

        .header-actions button:hover {
            background-color: white;
            color: #007bff;
        }

        /* Стили для слайдера */
        .slider {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
        }

        .slider-container {
            display: flex;
            width: 300%;
            transition: transform 1s ease;
        }

        .slide {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        /* Стили для подвала */
        .footer {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        .footer-left, .footer-center, .footer-right {
            width: 30%;
        }

        .footer-left h4, .footer-center h4, .footer-right h4 {
            margin-bottom: 10px;
        }

        .footer-center ul {
            list-style: none;
            padding: 0;
        }

        .footer-center ul li {
            margin: 5px 0;
        }

        .footer-center ul li a {
            color: white;
            text-decoration: none;
        }

        .footer-center ul li a:hover {
            text-decoration: underline;
        }

        .social-links a {
            color: white;
            text-decoration: none;
        }

        .social-links a:hover {
            text-decoration: underline;
        }

        .footer-bottom {
            text-align: center;
            background-color: #0056b3;
            padding: 10px;
        }
    </style>
</body>
</html>
