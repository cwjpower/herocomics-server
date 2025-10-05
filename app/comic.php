<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=UTF-8');

$host = getenv('DB_HOST') ?: 'mariadb';
$db   = getenv('DB_NAME') ?: 'herocomics';
$user = getenv('DB_USER') ?: 'hero';
$pass = getenv('DB_PASS') ?: '';
$mysqli = @mysqli_connect($host, $user, $pass, $db);
if ($mysqli) { $mysqli->set_charset('utf8mb4'); }

$slug = $_GET['slug'] ?? '';
$id = 0;
if (preg_match('/^book-(\d+)$/', $slug, $m)) { $id = (int)$m[1]; }

echo "<!doctype html><meta charset='utf-8'><title>히어로코믹스</title><h1>작품</h1>";
if ($id && $mysqli) {
    $stmt = $mysqli->prepare("SELECT id,isbn,title,author,unit_price FROM books WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    if ($r) {
        echo "<h2>".htmlspecialchars($r['title'])."</h2>";
        echo "<div>작가: ".htmlspecialchars($r['author'])."</div>";
        echo "<div>가격: ".(int)$r['unit_price']."</div>";
        echo "<div>ISBN: ".htmlspecialchars($r['isbn'])."</div>";
        echo "<p><a href='/'>← 목록</a></p>";
    } else {
        echo "작품을 찾을 수 없음 <a href='/'>← 목록</a>";
    }
} else {
    echo "잘못된 접근 <a href='/'>← 목록</a>";
}
