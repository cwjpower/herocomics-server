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
    $series_name = $_POST['series_name'] ?? '';
    $series_name_en = $_POST['series_name_en'] ?? '';
    $author = $_POST['author'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $cover_image = $_POST['cover_image'] ?? '';
    $status = $_POST['status'] ?? '';
    $total_volumes = $_POST['total_volumes'] ?? 0;

    if (empty($series_id)) {
        throw new Exception('series_id is required');
    }

    if (empty($series_name)) {
        throw new Exception('series_name is required');
    }

    // UPDATE 쿼리 동적 생성
    $updates = [];
    $params = [];
    $types = '';

    $updates[] = "series_name = ?";
    $params[] = $series_name;
    $types .= 's';

    if ($series_name_en !== '') {
        $updates[] = "series_name_en = ?";
        $params[] = $series_name_en;
        $types .= 's';
    }

    if ($author !== '') {
        $updates[] = "author = ?";
        $params[] = $author;
        $types .= 's';
    }

    if ($category !== '') {
        $updates[] = "category = ?";
        $params[] = $category;
        $types .= 's';
    }

    if ($description !== '') {
        $updates[] = "description = ?";
        $params[] = $description;
        $types .= 's';
    }

    if ($cover_image !== '') {
        $updates[] = "cover_image = ?";
        $params[] = $cover_image;
        $types .= 's';
    }

    if ($status !== '') {
        $updates[] = "status = ?";
        $params[] = $status;
        $types .= 's';
    }

    if ($total_volumes > 0) {
        $updates[] = "total_volumes = ?";
        $params[] = $total_volumes;
        $types .= 'i';
    }

    $updates[] = "updated_at = NOW()";

    // series_id 추가 (WHERE 조건용)
    $params[] = $series_id;
    $types .= 'i';

    $sql = "UPDATE bt_series SET " . implode(', ', $updates) . " WHERE series_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'code' => 0,
            'msg' => 'Series updated successfully',
            'series_id' => $series_id
        ], JSON_UNESCAPED_UNICODE);
    } else {
        throw new Exception('Failed to update series');
    }

} catch (Exception $e) {
    echo json_encode([
        'code' => 1,
        'msg' => 'Error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

mysqli_close($conn);
?>