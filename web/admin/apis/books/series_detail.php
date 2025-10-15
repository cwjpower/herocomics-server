<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $host = 'herocomics-mariadb';
    $dbname = 'herocomics';
    $username = 'root';
    $password = 'rootpass';

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $seriesId = isset($_GET['series_id']) ? (int)$_GET['series_id'] : 0;

    if ($seriesId <= 0) {
        throw new Exception('series_id is required');
    }

    $seriesSql = "
        SELECT 
            s.series_id,
            s.series_name,
            s.series_name_en,
            s.author,
            s.author_intro,
            s.category,
            s.comics_brand,
            s.description,
            s.publisher_review,
            s.cover_image,
            s.status,
            s.total_volumes,
            s.isbn,
            s.published_date,
            s.created_at,
            p.publisher_name
        FROM bt_series s
        LEFT JOIN bt_publishers p ON s.publisher_id = p.publisher_id
        WHERE s.series_id = :series_id
    ";

    $stmt = $pdo->prepare($seriesSql);
    $stmt->execute([':series_id' => $seriesId]);
    $series = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$series) {
        throw new Exception('Series not found');
    }

    $volumesSql = "
        SELECT 
            v.volume_id,
            v.volume_number,
            v.volume_title,
            v.cover_image,
            v.normal_price,
            v.price,
            v.discount_rate,
            v.is_free,
            v.total_pages,
            v.publish_date,
            v.status,
            v.created_at
        FROM bt_volumes v
        WHERE v.series_id = :series_id
        ORDER BY v.volume_number ASC
    ";

    $stmt = $pdo->prepare($volumesSql);
    $stmt->execute([':series_id' => $seriesId]);
    $volumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stats = [
        'total_volumes' => count($volumes),
        'published_volumes' => count(array_filter($volumes, fn($v) => $v['status'] === 'published')),
        'min_price' => !empty($volumes) ? min(array_column($volumes, 'price')) : 0,
        'max_price' => !empty($volumes) ? max(array_column($volumes, 'price')) : 0,
        'avg_discount' => !empty($volumes) ? round(array_sum(array_column($volumes, 'discount_rate')) / count($volumes), 1) : 0
    ];

    echo json_encode([
        'code' => 0,
        'msg' => 'success',
        'data' => [
            'series' => $series,
            'volumes' => $volumes,
            'stats' => $stats
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'code' => 400,
        'msg' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'code' => 500,
        'msg' => 'Database error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>