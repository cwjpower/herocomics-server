<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// DB 연결
$host = 'herocomics-mariadb';
$user = 'root';
$pass = 'rootpass';
$db = 'herocomics';
$conn = mysqli_connect($host, $user, $pass, $db);
mysqli_set_charset($conn, "utf8");

if (!$conn) {
    die(json_encode([
        'code' => 1,
        'msg' => 'DB Connection Error: ' . mysqli_connect_error()
    ]));
}

try {
    // 파라미터
    $series_id = $_GET['series_id'] ?? '';

    if (empty($series_id)) {
        throw new Exception('series_id is required');
    }

    // 시리즈 정보 조회
    $sql = "
        SELECT 
            s.series_id,
            s.publisher_id,
            s.series_name,
            s.series_name_en,
            s.author,
            s.category,
            s.description,
            s.cover_image,
            s.status,
            s.total_volumes,
            s.created_at,
            s.updated_at,
            p.publisher_name,
            p.publisher_code,
            COUNT(DISTINCT v.volume_id) as actual_volumes,
            COALESCE(SUM(v.total_pages), 0) as total_pages
        FROM bt_series s
        LEFT JOIN bt_publishers p ON s.publisher_id = p.publisher_id
        LEFT JOIN bt_volumes v ON s.series_id = v.series_id
        WHERE s.series_id = ?
        GROUP BY s.series_id
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $series_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode([
            'code' => 0,
            'msg' => 'success',
            'data' => $row
        ], JSON_UNESCAPED_UNICODE);
    } else {
        throw new Exception('Series not found');
    }

} catch (Exception $e) {
    echo json_encode([
        'code' => 1,
        'msg' => 'Error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

mysqli_close($conn);
?>