<?php
$dsn  = 'mysql:host=mariadb;port=3306;dbname='.(getenv('MARIADB_DATABASE') ?: 'herocomics').';charset=utf8mb4';
$user = getenv('MARIADB_USER') ?: 'hero';
$pass = getenv('MARIADB_PASSWORD') ?: 'heropass';
try {
  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
  $ver = $pdo->query("SELECT VERSION() AS v")->fetch()['v'] ?? 'unknown';
  echo "PDO: OK ($ver)";
} catch (Throwable $e) {
  http_response_code(500);
  echo "PDO: ERR -> " . $e->getMessage();
}
