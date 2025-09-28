<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');

require_once __DIR__ . '/../wps-vars.php';

$host = defined('DB_HOST') ? DB_HOST : (getenv('DB_HOST') ?: 'mariadb');
$user = defined('DB_USER') ? DB_USER : (getenv('DB_USER') ?: 'root');
$pass = defined('DB_PASS') ? DB_PASS : (getenv('DB_PASS') ?: '');
$db   = defined('DB_NAME') ? DB_NAME : (getenv('DB_NAME') ?: 'herocomics');
$port = defined('DB_PORT') ? (int)DB_PORT : (int)(getenv('DB_PORT') ?: 3306);

$mysqli = @new mysqli($host, $user, $pass, $db, $port);

if ($mysqli->connect_errno) {
    http_response_code(500);
    echo "db_ping=ERR {$mysqli->connect_error}\n";
    echo "host={$host} user={$user} db={$db} port={$port}\n";
    exit;
}

$res = $mysqli->query('SELECT 1 as ok');
$row = $res ? $res->fetch_assoc() : null;
echo ($row && (int)$row['ok'] === 1) ? "db_ping=OK\n" : "db_ping=ERR\n";