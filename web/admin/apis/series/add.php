<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['code' => 1, 'msg' => 'POST 요청만 허용됩니다.']);
    exit;
}

$db_host = 'herocomics-mariadb';
$db_name = 'herocomics';
$db_user = 'root';
$db_pass = 'rootpass';

try {
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $series_name = $_POST['series_name'] ?? '';
    $series_name_en = $_POST['series_name_en'] ?? null;
    $author = $_POST['author'] ?? null;
    $category = $_POST['category'] ?? 'MARVEL';
    $description = $_POST['description'] ?? null;
    $status = $_POST['status'] ?? 'ongoing';

    if (empty($series_name)) {
        echo json_encode(['code' => 1, 'msg' => '시리즈명을 입력해주세요.']);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO bt_series 
        (series_name, series_name_en, author, category, description, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([
        $series_name,
        $series_name_en,
        $author,
        $category,
        $description,
        $status
    ]);

    $series_id = $pdo->lastInsertId();

    echo json_encode([
        'code' => 0,
        'msg' => '시리즈가 추가되었습니다.',
        'data' => ['series_id' => $series_id]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'code' => 1,
        'msg' => 'DB 오류: ' . $e->getMessage()
    ]);
}
?>