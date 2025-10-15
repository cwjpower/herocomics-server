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
    $title = trim($_POST['title'] ?? '');
    $link_url = trim($_POST['link_url'] ?? '');
    $target = trim($_POST['target'] ?? '_self');
    $status = trim($_POST['status'] ?? 'show');
    $image_url = isset($_POST['image_url']) ? trim($_POST['image_url']) : null;
    $display_order = isset($_POST['display_order']) ? (int)$_POST['display_order'] : null;
    $start_date = trim($_POST['start_date'] ?? '');
    $end_date = trim($_POST['end_date'] ?? '');

    // 필수 값 체크
    if ($banner_id <= 0) {
        throw new Exception('배너 ID가 필요합니다.');
    }

    if (empty($title)) {
        throw new Exception('배너 제목은 필수입니다.');
    }

    // 존재 여부 체크
    $check_sql = "SELECT COUNT(*) as cnt FROM bt_banner WHERE ID = ?";
    $stmt = $db->prepare($check_sql);
    $stmt->execute([$banner_id]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];

    if ($exists == 0) {
        throw new Exception('존재하지 않는 배너입니다.');
    }

    // 파일 정보 업데이트 여부
    $file_update = '';
    $params = [];

    if ($image_url !== null) {
        $file_name = basename($image_url);
        $file_path = dirname($image_url) . '/';
        $file_update = ', bnr_file_path = ?, bnr_file_url = ?, bnr_file_name = ?';
        $params = [$file_path, $image_url, $file_name];
    }

    // 수정
    $sql = "UPDATE bt_banner 
            SET bnr_title = ?,
                bnr_url = ?,
                bnr_target = ?,
                hide_or_show = ?,
                bnr_order = COALESCE(?, bnr_order),
                bnr_from = ?,
                bnr_to = ?
                $file_update
            WHERE ID = ?";

    $update_params = [
        $title,
        $link_url,
        $target,
        $status,
        $display_order,
        $start_date ?: null,
        $end_date ?: null
    ];

    if (!empty($params)) {
        $update_params = array_merge($update_params, $params);
    }

    $update_params[] = $banner_id;

    $stmt = $db->prepare($sql);
    $stmt->execute($update_params);

    echo json_encode([
        'success' => true,
        'message' => '배너 정보가 수정되었습니다.'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}