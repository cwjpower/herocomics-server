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

    if (empty($input['series_id'])) {
        throw new Exception('series_id is required');
    }

    // 업데이트할 필드 동적 생성
    $updates = [];
    $params = [':series_id' => $input['series_id']];

    if (isset($input['series_name'])) {
        $updates[] = "series_name = :series_name";
        $params[':series_name'] = $input['series_name'];
    }
    if (isset($input['series_name_en'])) {
        $updates[] = "series_name_en = :series_name_en";
        $params[':series_name_en'] = $input['series_name_en'];
    }
    if (isset($input['author'])) {
        $updates[] = "author = :author";
        $params[':author'] = $input['author'];
    }
    if (isset($input['category'])) {
        $updates[] = "category = :category";
        $params[':category'] = $input['category'];
    }
    if (isset($input['description'])) {
        $updates[] = "description = :description";
        $params[':description'] = $input['description'];
    }
    if (isset($input['status'])) {
        $updates[] = "status = :status";
        $params[':status'] = $input['status'];
    }

    $updates[] = "updated_at = NOW()";

    if (empty($updates)) {
        throw new Exception('No fields to update');
    }

    $sql = "UPDATE bt_series SET " . implode(', ', $updates) . " WHERE series_id = :series_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode([
        'code' => 0,
        'msg' => 'Series updated successfully',
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