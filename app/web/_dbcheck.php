<?php declare(strict_types=1);
require __DIR__.'/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $now = $db->getVar('SELECT NOW()');
    echo json_encode(['ok' => true, 'db_time' => $now], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}