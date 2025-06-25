<?php

const DB_HOST = '';
const DB_PORT = '';
const DB_USER = '';
const DB_PASS = '';
const DB_NAME = '';
const SHORT_URL_LENGHT = 6;

function db_connect()
{
    static $db = null;

    if ($db === null) {
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;

        try {
            $db = new PDO($dsn, DB_USER, DB_PASS);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }  catch (PDOException $e) {
            die("Ошибка подключения: " . $e->getMessage());
        }
    }

    return $db;
}