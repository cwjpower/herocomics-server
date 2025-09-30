<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
if ($limit < 1 || $limit > 200) $limit = 50;

$host = getenv('DB_HOST') ?: 'mariadb';
$db   = getenv('DB_NAME') ?: 'herocomics';
$user = getenv('DB_USER') ?: 'hero';
$pass = getenv('DB_PASS') ?: '';

$mysqli = @mysqli_connect($host, $user, $pass, $db);
if (!$mysqli) {
    http_response_code(500);
    echo json_encode(['ok'=>False,'error'=>'db_connect','detail'=>mysqli_connect_error()], JSON_UNESCAPED_UNICODE);
    exit;
}
$mysqli->set_charset('utf8mb4');

$sql = "SELECT id, isbn, title, author, unit_price FROM books ORDER BY id DESC LIMIT ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $limit);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['ok'=>False,'error':'db_query','detail'=>$stmt->error], JSON_UNESCAPED_UNICODE);
    exit;
}
$res = $stmt->get_result();

$items = [];
while ($row = $res->fetch_assoc()) {
    $items[] = [
        'id'         => (int)$row['id'],
        'slug'       => 'book-'.(int)$row['id'],
        'isbn'       => $row['isbn'],
        'title'      => $row['title'],
        'author'     => $row['author'],
        'unit_price' => (int)$row['unit_price'],
        'synopsis'   => '',
    ];
}
echo json_encode(['ok'=>True, 'items'=>$items], JSON_UNESCAPED_UNICODE);
