<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');

$host = defined('DB_HOST') ? DB_HOST : (getenv('DB_HOST') ?: 'mariadb');
$user = defined('DB_USER') ? DB_USER : (getenv('DB_USER') ?: 'root');
$pass = defined('DB_PASS') ? DB_PASS : (getenv('DB_PASS') ?: '');
$name = defined('DB_NAME') ? DB_NAME : (getenv('DB_NAME') ?: 'herocomics');

if (!function_exists('mysqli_connect')) {
    echo "db_ping=ERR mysqli extension not loaded"; 
    exit;
}

$mysqli = @mysqli_connect($host, $user, $pass, $name);
if ($mysqli) {
    echo "db_ping=OK";
} else {
    echo "db_ping=ERR " . mysqli_connect_error();
}
