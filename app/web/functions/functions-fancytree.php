<?php
declare(strict_types=1);

// 이 파일은 /var/www/html/web/functions/ 경로에 위치.
// 기존 구문 오류(잘못된 주석/개행)를 제거하고 안전하게 교체.
if (!defined('ABS_PATH')) {
    // web/ 기준으로 상위가 /var/www/html
    define('ABS_PATH', dirname(__DIR__, 1));
}

require_once __DIR__ . "/functions-post.php";
