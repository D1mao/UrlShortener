<?php

require_once 'config.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$shortUrl = trim($path, '/');

try {
    $db = db_connect();
    $statement = $db->prepare("SELECT long_url FROM links WHERE short_url = :short_url");
    $statement->bindParam(':short_url', $shortUrl);
    $statement->execute();

    if ($statement->rowCount() > 0) {
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $long_url = $row['long_url'];

        header("Location: $long_url",  true, 301);
        exit;
    }
} catch (PDOException $e) {
    error_log("Ошибка перенаправления: ". $e->getMessage());
}

http_response_code(404);
include '404.html';
exit;
