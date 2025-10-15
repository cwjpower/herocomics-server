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

    $volumeId = isset($_GET['volume_id']) ? (int)$_GET['volume_id'] : 0;

    if ($volumeId <= 0) {
        throw new Exception('volume_id is required');
    }

    $sql = "
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
            v.created_at,
            s.series_id,
            s.series_name,
            s.series_name_en,
            s.author,
            s.author_intro,
            s.category,
            s.comics_brand,
            s.description,
            s.publisher_review,
            s.total_volumes,
            p.publisher_name
        FROM bt_volumes v
        INNER JOIN bt_series s ON v.series_id = s.series_id
        LEFT JOIN bt_publishers p ON v.publisher_id = p.publisher_id
        WHERE v.volume_id = :volume_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':volume_id' => $volumeId]);
    $volume = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$volume) {
        throw new Exception('Volume not found');
    }

    $relatedSql = "
        SELECT 
            volume_id,
            volume_number,
            volume_title,
            price,
            status
        FROM bt_volumes
        WHERE series_id = :series_id 
        AND volume_id != :volume_id
        AND status = 'published'
        ORDER BY volume_number ASC
        LIMIT 5
    ";

    $stmt = $pdo->prepare($relatedSql);
    $stmt->execute([
        ':series_id' => $volume['series_id'],
        ':volume_id' => $volumeId
    ]);
    $relatedVolumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'code' => 0,
        'msg' => 'success',
        'data' => [
            'volume' => $volume,
            'related_volumes' => $relatedVolumes
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