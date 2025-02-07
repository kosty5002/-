<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Если не авторизован, перенаправляем на страницу входа
    exit;
}

// Получаем данные о пользователе из сессии
$username = $_SESSION['username'];

// Инициализация переменных для ошибок и обновлений
$update_error = '';
$update_success = '';
$photo_path = ''; // Путь к изображению

// Логика обработки изменения данных и загрузки фотографии
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обработка изменения имени и email
    if (isset($_POST['update_name'])) {
        $new_username = htmlspecialchars(trim($_POST['username']));
        $new_email = htmlspecialchars(trim($_POST['email']));

        // Здесь добавьте проверку данных (например, проверка на уникальность email)
        // Также можно обновить информацию в базе данных, если это необходимо
        // Пример сохранения в сессии
        $_SESSION['username'] = $new_username;
        $update_success = 'Данные успешно обновлены!';
    }

    // Обработка загрузки фотографии
    if (isset($_FILES['profile_photo'])) {
        $file = $_FILES['profile_photo'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $update_error = 'Ошибка загрузки файла!';
        } else {
            // Проверяем тип файла
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowed_types)) {
                $update_error = 'Допустимы только изображения (JPEG, PNG, GIF)';
            } else {
                $file_name = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                $upload_dir = 'uploads/photos/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true); // Создаем папку, если она не существует
                }
                $file_path = $upload_dir . $file_name;
                if (move_uploaded_file($file['tmp_name'], $file_path)) {
                    $photo_path = $file_path;
                    // Здесь можно сохранить путь в базе данных, если необходимо
                } else {
                    $update_error = 'Не удалось загрузить изображение!';
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
    <title>Настройки профиля</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .profile-photo {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 50%;
        }

        .settings-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .settings-form input {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .settings-form button {
            padding: 10px 15px;
            font-size: 1rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .settings-form button:hover {
            background-color: #0056b3;
        }

        .error {
            color: #dc3545;
        }

        .success {
            color: #28a745;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="settings">
        <h2>Настройки профиля</h2>

        <?php if (!empty($update_error)): ?>
            <p class="error"><?= $update_error ?></p>
        <?php endif; ?>

        <?php if (!empty($update_success)): ?>
            <p class="success"><?= $update_success ?></p>
        <?php endif; ?>

        <form action="settings.php" method="POST" class="settings-form">
            <label for="username">Имя пользователя:</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($username) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($username) ?>@example.com" required>

            <button type="submit" name="update_name">Обновить данные</button>
        </form>

        <!-- Форма для загрузки новой фотографии -->
        <h3>Загрузить новое фото</h3>
        <form action="settings.php" method="POST" enctype="multipart/form-data">
            <label for="profile_photo">Выберите фотографию:</label>
            <input type="file" name="profile_photo" id="profile_photo" accept="image/*">
            <button type="submit">Загрузить</button>
        </form>

        <!-- Отображение текущей фотографии профиля -->
        <?php if (!empty($photo_path)): ?>
            <p><strong>Фото профиля:</strong></p>
            <img src="<?= $photo_path ?>" alt="Фото профиля" class="profile-photo">
        <?php else: ?>
            <p>Фото профиля не установлено.</p>
        <?php endif; ?>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
