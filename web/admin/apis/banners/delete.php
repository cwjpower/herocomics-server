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
    $db = db_connect();

    // POST 데이터 받기
    $banner_id = (int)($_POST['banner_id'] ?? 0);

    // 필수 값 체크
    if ($banner_id <= 0) {
        throw new Exception('배너 ID가 필요합니다.');
    }

    // 존재 여부 체크
    $check_sql = "SELECT bnr_file_url FROM bt_banner WHERE ID = ?";
    $stmt = $db->prepare($check_sql);
    $stmt->execute([$banner_id]);
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$banner) {
        throw new Exception('존재하지 않는 배너입니다.');
    }

    // 삭제
    $sql = "DELETE FROM bt_banner WHERE ID = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$banner_id]);

    // 이미지 파일 삭제 (선택적)
    // if (!empty($banner['bnr_file_url']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $banner['bnr_file_url'])) {
    //     unlink($_SERVER['DOCUMENT_ROOT'] . $banner['bnr_file_url']);
    // }

    echo json_encode([
        'success' => true,
        'message' => '배너가 삭제되었습니다.'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}