<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../../conf/db.php';

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        $volumeId = isset($_GET['volume_id']) ? intval($_GET['volume_id']) : null;
    } else {
        $volumeId = $input['volume_id'] ?? null;
    }

    if (empty($volumeId)) {
        throw new Exception('volume_id is required');
    }

    // 시리즈 ID 가져오기
    $getSeriesSql = "SELECT series_id FROM bt_volumes WHERE volume_id = :volume_id";
    $getSeriesStmt = $pdo->prepare($getSeriesSql);
    $getSeriesStmt->execute([':volume_id' => $volumeId]);
    $volume = $getSeriesStmt->fetch(PDO::FETCH_ASSOC);

    $seriesId = $volume ? $volume['series_id'] : null;

    // 권 삭제
    $sql = "DELETE FROM bt_volumes WHERE volume_id = :volume_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':volume_id' => $volumeId]);
    $deletedCount = $stmt->rowCount();

    if ($deletedCount === 0) {
        throw new Exception('Volume not found');
    }

    // 시리즈 updated_at 갱신
    if ($seriesId) {
        $updateSql = "UPDATE bt_series SET updated_at = NOW() WHERE series_id = :series_id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([':series_id' => $seriesId]);
    }

    echo json_encode([
        'code' => 0,
        'msg' => 'Volume deleted successfully',
        'deleted_count' => $deletedCount
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode([
        'code' => -1,
        'msg' => 'Error: ' . $e->getMessage(),
        'data' => null
    ], JSON_UNESCAPED_UNICODE);
}
?>