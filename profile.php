<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Если не авторизован, перенаправляем на страницу входа
    exit;
}

// Получаем данные о пользователе из сессии
$username = $_SESSION['username'];

// Если данные о пользователе не хранятся в сессии, инициализируем их
if (!isset($_SESSION['user_data'])) {
    $_SESSION['user_data'] = [
        'birth_year' => '',
        'city' => '',
        'address' => '',
        'profile_photo' => ''
    ];
}

// Переменные для данных о пользователе
$birth_year = $_SESSION['user_data']['birth_year'];
$city = $_SESSION['user_data']['city'];
$address = $_SESSION['user_data']['address'];
$profile_photo = $_SESSION['user_data']['profile_photo'];

// Обработка формы для обновления данных о пользователе
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['birth_year'])) {
        $birth_year = htmlspecialchars($_POST['birth_year']);
        $_SESSION['user_data']['birth_year'] = $birth_year;
    }
    if (isset($_POST['city'])) {
        $city = htmlspecialchars($_POST['city']);
        $_SESSION['user_data']['city'] = $city;
    }
    if (isset($_POST['address'])) {
        $address = htmlspecialchars($_POST['address']);
        $_SESSION['user_data']['address'] = $address;
    }

    // Обработка загрузки фотографии
    $photo_error = '';
    $photo_path = '';  // Путь к изображению

    if (isset($_FILES['profile_photo'])) {
        $file = $_FILES['profile_photo'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $photo_error = 'Ошибка загрузки файла!';
        } else {
            // Проверяем тип файла (например, только изображения)
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowed_types)) {
                $photo_error = 'Допустимы только изображения (JPEG, PNG, GIF)';
            } else {
                // Генерируем уникальное имя для файла и сохраняем его
                $file_name = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                $upload_dir = 'uploads/photos/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);  // Создаем папку, если она не существует
                }
                $file_path = $upload_dir . $file_name;
                if (move_uploaded_file($file['tmp_name'], $file_path)) {
                    $photo_path = $file_path;
                    $_SESSION['user_data']['profile_photo'] = $photo_path;
                } else {
                    $photo_error = 'Не удалось загрузить изображение!';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .profile-photo {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 50%;
        }

        .settings-button, .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .settings-button:hover, .back-button:hover {
            background-color: #0056b3;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .profile {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 1rem;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        input[type="date"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            width: 100%;
            box-sizing: border-box;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        h3 {
            font-size: 1.2rem;
            margin-top: 30px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <section class="profile">
        <h2>Профиль пользователя</h2>
        <p><strong>Имя:</strong> <?= htmlspecialchars($username) ?></p>

        <!-- Отображаем фотографию пользователя, если она есть -->
        <?php if (!empty($profile_photo)): ?>
            <p><strong>Фото профиля:</strong></p>
            <img src="<?= htmlspecialchars($profile_photo) ?>" alt="Фото профиля" class="profile-photo">
        <?php else: ?>
            <p>Фото профиля не установлено.</p>
        <?php endif; ?>

        <h3>Загрузить фото</h3>
        <?php if (!empty($photo_error)): ?>
            <p class="error"><?= $photo_error ?></p>
        <?php endif; ?>
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <label for="profile_photo">Выберите фотографию:</label>
            <input type="file" name="profile_photo" id="profile_photo" accept="image/*">
            <button type="submit">Загрузить</button>
        </form>

        <h3>Информация о себе</h3>
        <form action="profile.php" method="POST">
            <label for="birth_year">Год рождения:</label>
            <input type="date" name="birth_year" id="birth_year" value="<?= htmlspecialchars($birth_year) ?>" required>

            <label for="city">Город:</label>
            <input type="text" name="city" id="city" value="<?= htmlspecialchars($city) ?>" required>

            <label for="address">Адрес для доставки:</label>
            <input type="text" name="address" id="address" value="<?= htmlspecialchars($address) ?>" required>

            <button type="submit">Сохранить изменения</button>
        </form>

        <a href="settings.php" class="settings-button">Настройки профиля</a>
        
        <!-- Кнопка вернуться в каталог -->
        <a href="catalog.php" class="back-button">Вернуться в каталог</a>
    </section>
</body>
</html>
