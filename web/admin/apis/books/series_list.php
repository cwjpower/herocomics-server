<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../conf/db.php';

try {
    // 파라미터 받기
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
    $offset = ($page - 1) * $limit;

    // WHERE 조건
    $where = "WHERE s.status IN ('ongoing', 'completed')";
    if ($category !== 'all') {
        $where .= " AND s.category = :category";
    }

    // 전체 개수
    $countSql = "SELECT COUNT(*) as total FROM bt_series s $where";
    $countStmt = $pdo->prepare($countSql);
    if ($category !== 'all') {
        $countStmt->bindParam(':category', $category);
    }
    $countStmt->execute();
    $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 시리즈 목록
    $sql = "
        SELECT 
            s.series_id,
            s.series_name,
            s.series_name_en,
            s.author,
            s.category,
            s.description,
            s.status,
            s.publisher_id,
            p.publisher_name,
            s.created_at,
            s.updated_at,
            COUNT(DISTINCT v.volume_id) as total_volumes,
            COUNT(DISTINCT CASE WHEN v.status = 'published' THEN v.volume_id END) as available_volumes
        FROM bt_series s
        LEFT JOIN bt_publishers p ON s.publisher_id = p.publisher_id
        LEFT JOIN bt_volumes v ON s.series_id = v.series_id
        $where
        GROUP BY s.series_id
        ORDER BY s.updated_at DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);
    if ($category !== 'all') {
        $stmt->bindParam(':category', $category);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $series = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 응답
    echo json_encode([
        'code' => 0,
        'msg' => 'Success',
        'data' => [
            'series' => $series,
            'pagination' => [
                'current_page' => $page,
                'total_count' => intval($totalCount),
                'total_pages' => ceil($totalCount / $limit),
                'limit' => $limit
            ]
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode([
        'code' => -1,
        'msg' => 'Error: ' . $e->getMessage(),
        'data' => null
    ], JSON_UNESCAPED_UNICODE);
}
?>