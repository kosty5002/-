<?php
// include 'header.php';
?>

<main class="contact-page">
    <h2>Свяжитесь с нами</h2>
    <p>Если у вас есть вопросы или предложения, заполните форму ниже, и мы свяжемся с вами в ближайшее время.</p>

    <form action="contact-submit.php" method="POST" class="contact-form">
        <label for="name">Ваше имя:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Ваш Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Ваше сообщение:</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button type="submit">Отправить</button>
    </form>

    <div class="back-home">
        <a href="index.php" class="btn">На главную</a>
    </div>
</main>

<style>
.contact-page {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.contact-page h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 2em;
    color: #333;
}

.contact-page p {
    text-align: center;
    margin-bottom: 30px;
    color: #666;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.contact-form label {
    font-weight: bold;
    color: #333;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1em;
    box-sizing: border-box;
}

.contact-form button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 1em;
    cursor: pointer;
    transition: background-color 0.3s;
}

.contact-form button:hover {
    background-color: #0056b3;
}

.back-home {
    text-align: center;
    margin-top: 20px;
}

.back-home .btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #28a745;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-size: 1em;
    transition: background-color 0.3s;
}

.back-home .btn:hover {
    background-color: #218838;
}
</style>

<?php
// include 'footer.php';
?>
