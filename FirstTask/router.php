<?php

require_once 'config.php';

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$shortUrl = trim($path, '/');

if($shortUrl == ''){
    require 'index.php';
    return;
}

if (preg_match('/^[a-zA-Z0-9]{'.SHORT_URL_LENGHT.'}$/', $shortUrl)) {
    require 'redirect.php';
    return;
}
