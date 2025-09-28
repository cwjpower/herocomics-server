<?php
$host = 'mariadb';
$user = 'hero';
$pass = getenv('MARIADB_PASSWORD') ?: 'heropass';
$db   = getenv('MARIADB_DATABASE') ?: 'herocomics';
$port = 3306;

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($host, $user, $pass, $db, $port);
if (!$conn) {
    http_response_code(500);
    echo "DB(hero): ERR -> " . mysqli_connect_error();
    exit;
}
$row = $conn->query("SELECT CURRENT_USER() AS u, VERSION() AS v")->fetch_assoc();
$u = $row['u'] ?? 'unknown';
$v = $row['v'] ?? 'unknown';
echo "DB(hero): OK - $u / $v";
