<?php

function generateShortCode()
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < SHORT_URL_LENGHT; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }

    return $code;
}

function isValidUrl($url)
{
    return (filter_var($url, FILTER_VALIDATE_URL) !== false);
}

function escapeHTML($string)
{
    return htmlspecialchars($string, ENT_QUOTES,  'UTF-8');
}
