<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// OPTIONS 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../../conf/db.php';

try {
    // POST 데이터 받기
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        throw new Exception('Invalid JSON input');
    }

    // 필수 필드 검증
    if (empty($input['series_name']) || empty($input['author']) || empty($input['category'])) {
        throw new Exception('Required fields: series_name, author, category');
    }

    // 시리즈 추가
    $sql = "
        INSERT INTO bt_series (
            series_name,
            series_name_en,
            author,
            category,
            description,
            status,
            publisher_id,
            created_at,
            updated_at
        ) VALUES (
            :series_name,
            :series_name_en,
            :author,
            :category,
            :description,
            :status,
            :publisher_id,
            NOW(),
            NOW()
        )
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':series_name' => $input['series_name'],
        ':series_name_en' => $input['series_name_en'] ?? null,
        ':author' => $input['author'],
        ':category' => $input['category'],
        ':description' => $input['description'] ?? null,
        ':status' => $input['status'] ?? 'ongoing',
        ':publisher_id' => $input['publisher_id'] ?? 1
    ]);

    $seriesId = $pdo->lastInsertId();

    echo json_encode([
        'code' => 0,
        'msg' => 'Series added successfully',
        'series_id' => intval($seriesId)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode([
        'code' => -1,
        'msg' => 'Error: ' . $e->getMessage(),
        'data' => null
    ], JSON_UNESCAPED_UNICODE);
}
?>