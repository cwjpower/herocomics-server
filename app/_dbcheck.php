<?php
header('Content-Type: text/plain; charset=utf-8');
$host = getenv('DB_HOST') ?: 'mariadb';
$db   = getenv('DB_NAME') ?: 'herocomics';
$user = getenv('DB_USER') ?: 'hero';
$pass = getenv('DB_PASS') ?: '';
$mysqli = @mysqli_connect($host, $user, $pass, $db);
if (!$mysqli) { echo "DB: ERR " . mysqli_connect_error() . PHP_EOL; http_response_code(500); exit(1); }
$res = $mysqli->query("SELECT VERSION() AS v");
$row = $res ? $res->fetch_assoc() : null;
echo "DB: OK (" . ($row['v'] ?? 'unknown') . ")" . PHP_EOL;