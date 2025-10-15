<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../../conf/db.php';

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        throw new Exception('Invalid JSON input');
    }

    if (empty($input['series_id']) || empty($input['volume_number']) || empty($input['volume_title'])) {
        throw new Exception('Required fields: series_id, volume_number, volume_title');
    }

    $sql = "
        INSERT INTO bt_volumes (
            series_id,
            volume_number,
            volume_title,
            price,
            discount_rate,
            is_free,
            status,
            created_at,
            updated_at
        ) VALUES (
            :series_id,
            :volume_number,
            :volume_title,
            :price,
            :discount_rate,
            :is_free,
            :status,
            NOW(),
            NOW()
        )
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':series_id' => $input['series_id'],
        ':volume_number' => $input['volume_number'],
        ':volume_title' => $input['volume_title'],
        ':price' => $input['price'] ?? 0,
        ':discount_rate' => $input['discount_rate'] ?? 0,
        ':is_free' => $input['is_free'] ?? 0,
        ':status' => $input['status'] ?? 'draft'
    ]);

    $volumeId = $pdo->lastInsertId();

    // 시리즈 updated_at 갱신
    $updateSql = "UPDATE bt_series SET updated_at = NOW() WHERE series_id = :series_id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([':series_id' => $input['series_id']]);

    echo json_encode([
        'code' => 0,
        'msg' => 'Volume added successfully',
        'volume_id' => intval($volumeId)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode([
        'code' => -1,
        'msg' => 'Error: ' . $e->getMessage(),
        'data' => null
    ], JSON_UNESCAPED_UNICODE);
}
?>