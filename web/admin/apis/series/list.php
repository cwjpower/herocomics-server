<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$db_host = 'herocomics-mariadb';
$db_name = 'herocomics';
$db_user = 'root';
$db_pass = 'rootpass';

try {
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $series_id = $_GET['series_id'] ?? 0;

    if (empty($series_id)) {
        echo json_encode(['code' => 1, 'msg' => 'series_id가 필요합니다.']);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT 
            v.volume_id,
            v.series_id,
            v.volume_number,
            v.volume_title,
            v.price,
            v.is_free,
            v.status,
            v.created_at,
            COUNT(p.page_id) as total_pages
        FROM bt_volumes v
        LEFT JOIN bt_pages p ON v.volume_id = p.volume_id
        WHERE v.series_id = ?
        GROUP BY v.volume_id
        ORDER BY v.volume_number ASC
    ");

    $stmt->execute([$series_id]);
    $volumes = $stmt->fetchAll();

    echo json_encode([
        'code' => 0,
        'msg' => '성공',
        'data' => $volumes
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'code' => 1,
        'msg' => 'DB 오류: ' . $e->getMessage()
    ]);
}
?>