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
        // GET 파라미터로도 받기
        $seriesId = isset($_GET['series_id']) ? intval($_GET['series_id']) : null;
    } else {
        $seriesId = $input['series_id'] ?? null;
    }

    if (empty($seriesId)) {
        throw new Exception('series_id is required');
    }

    // 트랜잭션 시작
    $pdo->beginTransaction();

    // 관련 권들도 함께 삭제 (또는 status를 'deleted'로 변경)
    $sql = "DELETE FROM bt_volumes WHERE series_id = :series_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':series_id' => $seriesId]);
    $deletedVolumes = $stmt->rowCount();

    // 시리즈 삭제
    $sql = "DELETE FROM bt_series WHERE series_id = :series_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':series_id' => $seriesId]);
    $deletedSeries = $stmt->rowCount();

    $pdo->commit();

    if ($deletedSeries === 0) {
        throw new Exception('Series not found');
    }

    echo json_encode([
        'code' => 0,
        'msg' => 'Series deleted successfully',
        'deleted_series' => $deletedSeries,
        'deleted_volumes' => $deletedVolumes
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'code' => -1,
        'msg' => 'Error: ' . $e->getMessage(),
        'data' => null
    ], JSON_UNESCAPED_UNICODE);
}
?>