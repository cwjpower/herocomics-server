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

    // JSON 데이터 받기
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!isset($data['banners']) || !is_array($data['banners'])) {
        throw new Exception('배너 순서 데이터가 필요합니다.');
    }

    $db->beginTransaction();

    try {
        $sql = "UPDATE bt_banner SET bnr_order = ? WHERE ID = ?";
        $stmt = $db->prepare($sql);

        foreach ($data['banners'] as $banner) {
            if (!isset($banner['banner_id']) || !isset($banner['order'])) {
                continue;
            }

            $stmt->execute([
                (int)$banner['order'],
                (int)$banner['banner_id']
            ]);
        }

        $db->commit();

        echo json_encode([
            'success' => true,
            'message' => '배너 순서가 변경되었습니다.'
        ], JSON_UNESCAPED_UNICODE);

    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}