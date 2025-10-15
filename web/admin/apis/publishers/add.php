<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['code' => 1, 'msg' => 'POST 요청만 허용됩니다.'], JSON_UNESCAPED_UNICODE);
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

    // 필수 파라미터 확인
    $publisher_name = $_POST['publisher_name'] ?? '';
    $publisher_code = $_POST['publisher_code'] ?? '';

    if (empty($publisher_name)) {
        echo json_encode(['code' => 1, 'msg' => '출판사명을 입력해주세요.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (empty($publisher_code)) {
        echo json_encode(['code' => 1, 'msg' => '출판사 코드를 입력해주세요.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 코드 중복 확인
    $check = $pdo->prepare("SELECT publisher_id FROM bt_publishers WHERE publisher_code = ?");
    $check->execute([$publisher_code]);

    if ($check->fetch()) {
        echo json_encode(['code' => 1, 'msg' => '이미 사용 중인 출판사 코드입니다.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 출판사 추가
    $stmt = $pdo->prepare("
        INSERT INTO bt_publishers 
        (publisher_name, publisher_code, status, created_at)
        VALUES (?, ?, 'active', NOW())
    ");

    $stmt->execute([
        $publisher_name,
        strtoupper($publisher_code)
    ]);

    $publisher_id = $pdo->lastInsertId();

    echo json_encode([
        'code' => 0,
        'msg' => '출판사가 추가되었습니다.',
        'data' => [
            'publisher_id' => $publisher_id,
            'publisher_name' => $publisher_name,
            'publisher_code' => strtoupper($publisher_code)
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        'code' => 1,
        'msg' => 'DB 오류: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode([
        'code' => 1,
        'msg' => '오류: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>