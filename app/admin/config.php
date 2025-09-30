<?php
declare(strict_types=1);
$DB_HOST = getenv('DB_HOST') ?: 'mariadb';
$DB_PORT = (string)(getenv('DB_PORT') ?: '3306');
$DB_NAME = getenv('DB_NAME') ?: 'herocomics';
$DB_USER = getenv('DB_USER') ?: 'hero';
$DB_PASS = getenv('DB_PASS') ?: 'secret';
$dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
