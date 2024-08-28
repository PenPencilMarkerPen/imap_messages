
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Auth</title>
</head>
<body>
<header>
        <div class="container">
        <a href="/app/templates/messages.php">Почта</a>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h1>Авторизация</h1>
            <form action="/app/main.php" method="POST">
                <p>Логин: <input type="email" placeholder="Введите логин" name="login" required /></p>
                <p>Пароль: <input type="password" placeholder="Введите пароль" name="password" required /></p>
                <p>Сервер: <input type="text" placeholder="Введите сервер" name="server" required /></p>
                <p>Порт: <input type="number" placeholder="Введите порт" name="port" required /></p>
                <p>Только новые сообщения: <input type="checkbox" name="check" checked="checked" value="true"/> </p>
                <?php if(isset($_SESSION['err'])){
                    echo '<p style="color: red !important">'.$_SESSION['err'].'</p>';
                }
                ?>
                <input type="submit" value="Войти">
            </form>
        </div>
    </div>
</body>
</html>


<?php
session_destroy();
?>