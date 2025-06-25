<?php

require_once 'config.php';

try {
    $db = db_connect();

    $query = "CREATE TABLE IF NOT EXISTS links (
        id SERIAL PRIMARY KEY,
        long_url TEXT NOT NULL,
        short_url VARCHAR(50) NOT NULL UNIQUE)";

    $db->exec($query);

    echo "База данных успешно создана!";
}  catch (PDOException $e) {
    die("Ошибка при создании бд: " . $e->getMessage());
}