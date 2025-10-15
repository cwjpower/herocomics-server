<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT, OPTIONS');
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

    if (empty($input['volume_id'])) {
        throw new Exception('volume_id is required');
    }

    $updates = [];
    $params = [':volume_id' => $input['volume_id']];
    $seriesId = null;

    if (isset($input['volume_number'])) {
        $updates[] = "volume_number = :volume_number";
        $params[':volume_number'] = $input['volume_number'];
    }
    if (isset($input['volume_title'])) {
        $updates[] = "volume_title = :volume_title";
        $params[':volume_title'] = $input['volume_title'];
    }
    if (isset($input['price'])) {
        $updates[] = "price = :price";
        $params[':price'] = $input['price'];
    }
    if (isset($input['discount_rate'])) {
        $updates[] = "discount_rate = :discount_rate";
        $params[':discount_rate'] = $input['discount_rate'];
    }
    if (isset($input['is_free'])) {
        $updates[] = "is_free = :is_free";
        $params[':is_free'] = $input['is_free'];
    }
    if (isset($input['status'])) {
        $updates[] = "status = :status";
        $params[':status'] = $input['status'];
    }

    $updates[] = "updated_at = NOW()";

    if (count($updates) === 1) {
        throw new Exception('No fields to update');
    }

    // 시리즈 ID 가져오기
    $getSeriesSql = "SELECT series_id FROM bt_volumes WHERE volume_id = :volume_id";
    $getSeriesStmt = $pdo->prepare($getSeriesSql);
    $getSeriesStmt->execute([':volume_id' => $input['volume_id']]);
    $volume = $getSeriesStmt->fetch(PDO::FETCH_ASSOC);

    if ($volume) {
        $seriesId = $volume['series_id'];
    }

    // 권 업데이트
    $sql = "UPDATE bt_volumes SET " . implode(', ', $updates) . " WHERE volume_id = :volume_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // 시리즈 updated_at 갱신
    if ($seriesId) {
        $updateSql = "UPDATE bt_series SET updated_at = NOW() WHERE series_id = :series_id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([':series_id' => $seriesId]);
    }

    echo json_encode([
        'code' => 0,
        'msg' => 'Volume updated successfully',
        'affected_rows' => $stmt->rowCount()
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode([
        'code' => -1,
        'msg' => 'Error: ' . $e->getMessage(),
        'data' => null
    ], JSON_UNESCAPED_UNICODE);
}
?>