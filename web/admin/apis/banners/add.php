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
    $title = trim($_POST['title'] ?? '');
    $link_url = trim($_POST['link_url'] ?? '');
    $target = trim($_POST['target'] ?? '_self');
    $status = trim($_POST['status'] ?? 'show');
    $image_url = trim($_POST['image_url'] ?? '');
    $display_order = isset($_POST['display_order']) ? (int)$_POST['display_order'] : 999;
    $start_date = trim($_POST['start_date'] ?? '');
    $end_date = trim($_POST['end_date'] ?? '');
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 1;

    // 필수 값 체크
    if (empty($title)) {
        throw new Exception('배너 제목은 필수입니다.');
    }

    if (empty($image_url)) {
        throw new Exception('배너 이미지는 필수입니다.');
    }

    // 파일 경로/이름 분리
    $file_name = basename($image_url);
    $file_path = dirname($image_url) . '/';

    // 추가
    $sql = "INSERT INTO bt_banner 
            (bnr_title, bnr_url, bnr_target, hide_or_show, 
             bnr_file_path, bnr_file_url, bnr_file_name, 
             bnr_created, bnr_order, bnr_from, bnr_to, user_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        $title,
        $link_url,
        $target,
        $status,
        $file_path,
        $image_url,
        $file_name,
        $display_order,
        $start_date ?: null,
        $end_date ?: null,
        $user_id
    ]);

    $banner_id = $db->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => '배너가 추가되었습니다.',
        'banner_id' => $banner_id
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}