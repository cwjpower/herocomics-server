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
    $series_id = $_POST['series_id'] ?? '';

    if (empty($series_id)) {
        throw new Exception('series_id is required');
    }

    // 권이 있는지 확인
    $check_sql = "SELECT COUNT(*) as volume_count FROM bt_volumes WHERE series_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $series_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    $check_row = mysqli_fetch_assoc($check_result);

    if ($check_row['volume_count'] > 0) {
        throw new Exception('Cannot delete series with existing volumes. Please delete all volumes first.');
    }

    // 시리즈 삭제
    $sql = "DELETE FROM bt_series WHERE series_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $series_id);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode([
                'code' => 0,
                'msg' => 'Series deleted successfully'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            throw new Exception('Series not found');
        }
    } else {
        throw new Exception('Failed to delete series');
    }

} catch (Exception $e) {
    echo json_encode([
        'code' => 1,
        'msg' => 'Error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

mysqli_close($conn);
?>