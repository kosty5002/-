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
        'terms_title' => 'Условия использования',
        'terms_intro' => 'Пожалуйста, внимательно ознакомьтесь с этими условиями использования. Используя наш сайт, вы соглашаетесь с нижеприведёнными условиями.',
        'terms_content' => [
            'section_1' => '1. Общие положения',
            'section_1_desc' => 'Данный сайт предоставляет информацию о товарах и услугах, а также позволяет их заказывать. Все товары, представленные на сайте, принадлежат их соответствующим владельцам и производителям.',
            
            'section_2' => '2. Заказы и оплата',
            'section_2_desc' => 'После размещения заказа на нашем сайте вы обязуетесь оплатить заказ в соответствии с условиями, указанными при оформлении.',
            
            'section_3' => '3. Доставка и возврат',
            'section_3_desc' => 'Мы обеспечиваем доставку по всей территории страны. Возврат товаров возможен в течение 14 дней с момента получения при соблюдении условий возврата.',
            
            'section_4' => '4. Ответственность',
            'section_4_desc' => 'Мы не несем ответственности за любые технические сбои, ошибки или неполадки, связанные с работой сайта, а также за ущерб, причиненный в результате использования материалов с сайта.',
            
            'section_5' => '5. Изменения условий',
            'section_5_desc' => 'Мы оставляем за собой право изменять условия использования сайта в любое время. Изменения вступают в силу с момента их публикации на сайте.',
        ],
    ],
    'en' => [
        'terms_title' => 'Terms of Use',
        'terms_intro' => 'Please read these terms of use carefully. By using our website, you agree to the following terms.',
        'terms_content' => [
            'section_1' => '1. General Provisions',
            'section_1_desc' => 'This website provides information about products and services, and allows you to place orders. All products presented on the site belong to their respective owners and manufacturers.',
            
            'section_2' => '2. Orders and Payment',
            'section_2_desc' => 'Once you place an order on our website, you agree to pay for the order according to the terms specified during checkout.',
            
            'section_3' => '3. Delivery and Returns',
            'section_3_desc' => 'We provide delivery across the entire country. Returns are allowed within 14 days from the date of receipt, subject to return conditions.',
            
            'section_4' => '4. Liability',
            'section_4_desc' => 'We are not responsible for any technical failures, errors, or issues related to the operation of the website, or for damages caused by the use of materials from the website.',
            
            'section_5' => '5. Changes to Terms',
            'section_5_desc' => 'We reserve the right to change the terms of use of the website at any time. Changes take effect immediately upon publication on the website.',
        ],
    ],
];

$text = $texts[$lang];
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $text['terms_title'] ?> - Магазин оборудования</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .terms-section {
            padding: 40px 20px;
            background-color: #f8f9fa;
            text-align: left;
            max-width: 900px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .terms-section h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .terms-section h3 {
            font-size: 20px;
            color: #007bff;
            margin-top: 30px;
        }

        .terms-section p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="terms-section">
        <h2><?= $text['terms_title'] ?></h2>
        <p><?= $text['terms_intro'] ?></p>

        <h3><?= $text['terms_content']['section_1'] ?></h3>
        <p><?= $text['terms_content']['section_1_desc'] ?></p>

        <h3><?= $text['terms_content']['section_2'] ?></h3>
        <p><?= $text['terms_content']['section_2_desc'] ?></p>

        <h3><?= $text['terms_content']['section_3'] ?></h3>
        <p><?= $text['terms_content']['section_3_desc'] ?></p>

        <h3><?= $text['terms_content']['section_4'] ?></h3>
        <p><?= $text['terms_content']['section_4_desc'] ?></p>

        <h3><?= $text['terms_content']['section_5'] ?></h3>
        <p><?= $text['terms_content']['section_5_desc'] ?></p>

        <a href="index.php" class="btn"><?= $text['to_homepage'] ?? 'Перейти на главную' ?></a>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
