<?php
    session_start();

    require_once(__DIR__.'/../Mail/Mail.php');
    
    use App\Mail;
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Messages</title>
</head>
<body>

    <header>
        <div class="container">
            <a href="/app/templates/index.php">Войти</a>
        </div>
    </header>

    <div class="container">
        <?php
            $messages = array();
            $dir = '/app/../files/';
            if (isset($_SESSION['messages'])) {
                $messages = unserialize($_SESSION['messages']);
            }
            if (empty($messages)) {
                echo '<h1>Почта пуста или вы не авторизованы!</h1>';
            } else {
                foreach ($messages as $message) {
        ?>
        <div class="message">
            <h2>ID: <?= $message->getId() ?></h2>
            <h2>From: <?= $message->getEmail() ?></h2>
            <h2>Заголовок: <?= $message->getHeader()?></h2>
            <p>Сообщение: <?= $message->getBody() ?></p>
            <?php if (!empty($message->getFiles())): ?>
            <ul>
                <?php foreach ($message->getFiles() as $file): ?>
                <li><a href="<?= $dir.$file ?>"><?= $file ?></a></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        <?php 
                }
            }
        ?>
    </div>
    
</body>
</html>
