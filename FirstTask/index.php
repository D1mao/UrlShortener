<?php

require_once 'config.php';
require_once 'functions.php';

$error = '';
$shortUrl = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $longUrl = $_POST['longUrl'] ?? '';

    if (!isValidUrl($longUrl)) {
        $error = 'Пожалуйста, введите корректный URL';
    } else {
        try {
            $db = db_connect();

            $statement = $db->prepare("SELECT short_url FROM links WHERE long_url = :url");
            $statement->bindParam(':url', $longUrl);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                $row = $statement->fetch(PDO::FETCH_ASSOC);
                $shortUrl = $row['short_url'];
            } else {
                $attemptsCounter = 0;
                $maxAttempts = 10;
                $shortUrl = '';

                do {
                    $shortUrl = generateShortCode();
                    $statement = $db->prepare("SELECT short_url FROM links WHERE short_url = :shortUrl");
                    $statement->bindParam(':shortUrl', $shortUrl);
                    $statement->execute();
                    $exists = $statement->rowCount() > 0;
                    $attemptsCounter++;
                } while ($exists && $attemptsCounter < $maxAttempts);

                if ($exists) {
                    throw new Exception("Не удалось сгенерировать короткую ссылку");
                }

                $statement = $db->prepare("INSERT INTO links (short_url, long_url) VALUES (:shortUrl, :longUrl)");
                $statement->bindParam(':shortUrl', $shortUrl);
                $statement->bindParam(':longUrl', $longUrl);
                $statement->execute();
            }

            $baseUrl = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . escapeHTML($_SERVER["HTTP_HOST"]);
            $shortUrl = $baseUrl . '/' . $shortUrl;
        } catch (Exception $e) {
            $error = 'Ошибка сервера: ' .escapeHTML($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сокращение ссылок</title>
</head>
<body>
<h1>Сократить ссылку</h1>

<?php if ($error): ?>
    <div class="error"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <input type="url" name="longUrl" placeholder="Введите вашу ссылку" required autocomplete="off">
    </div>
    <button type="submit">Сократить</button>
</form>

<?php if ($shortUrl): ?>
    <div class="result">
        Ваша короткая ссылка:<br>
        <a href="<?= $shortUrl ?>" target="_blank"><?= $shortUrl ?></a>
    </div>
<?php endif; ?>
</body>
</html>