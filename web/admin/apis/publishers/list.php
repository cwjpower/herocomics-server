<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

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

    // 출판사 목록 조회
    $stmt = $pdo->prepare("
        SELECT 
            publisher_id,
            publisher_name,
            publisher_code,
            publisher_name_ko,
            contact_email,
            contact_phone,
            commission_rate,
            status,
            approval_date,
            created_at,
            (SELECT COUNT(*) FROM bt_series WHERE publisher_id = bt_publishers.publisher_id) as series_count,
            (SELECT COUNT(*) FROM bt_volumes v 
             JOIN bt_series s ON v.series_id = s.series_id 
             WHERE s.publisher_id = bt_publishers.publisher_id) as volume_count
        FROM bt_publishers
        ORDER BY 
            FIELD(status, 'active', 'pending', 'suspended'),
            created_at DESC
    ");

    $stmt->execute();
    $publishers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'code' => 0,
        'msg' => '성공',
        'data' => $publishers
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        'code' => 1,
        'msg' => 'DB 오류: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode([
        'code' => 1,
        'msg' => '오류: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>