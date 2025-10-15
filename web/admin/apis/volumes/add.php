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

    $series_id = $_POST['series_id'] ?? 0;
    $volume_number = $_POST['volume_number'] ?? 0;
    $volume_title = $_POST['volume_title'] ?? null;
    $price = $_POST['price'] ?? 3990;
    $is_free = $_POST['is_free'] ?? 0;
    $status = $_POST['status'] ?? 'published';

    if (empty($series_id) || empty($volume_number)) {
        echo json_encode(['code' => 1, 'msg' => 'series_id와 volume_number가 필요합니다.']);
        exit;
    }

    // 중복 체크
    $check = $pdo->prepare("
        SELECT volume_id 
        FROM bt_volumes 
        WHERE series_id = ? AND volume_number = ?
    ");
    $check->execute([$series_id, $volume_number]);

    if ($check->fetch()) {
        echo json_encode(['code' => 1, 'msg' => '이미 존재하는 권 번호입니다.']);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO bt_volumes 
        (series_id, volume_number, volume_title, price, is_free, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([
        $series_id,
        $volume_number,
        $volume_title,
        $price,
        $is_free,
        $status
    ]);

    $volume_id = $pdo->lastInsertId();

    echo json_encode([
        'code' => 0,
        'msg' => '볼륨이 추가되었습니다.',
        'data' => ['volume_id' => $volume_id]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'code' => 1,
        'msg' => 'DB 오류: ' . $e->getMessage()
    ]);
}
?>