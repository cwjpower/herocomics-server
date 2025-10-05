<?php
error_reporting(E_ALL); ini_set('display_errors','1');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db(): mysqli {
  static $conn;
  if ($conn) return $conn;
  $host = getenv('DB_HOST') ?: 'mariadb';
  $port = (int)(getenv('DB_PORT') ?: 3306);
  $db   = getenv('MARIADB_DATABASE') ?: 'herocomics';
  $user = getenv('MARIADB_USER') ?: 'hero';
  $pass = getenv('MARIADB_PASSWORD') ?: 'heropass';
  $conn = new mysqli($host, $user, $pass, $db, $port);
  $conn->set_charset('utf8mb4');
  return $conn;
}

function json_out($data, int $status=200): void {
  http_response_code($status);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  exit;
}