<?php
declare(strict_types=1);

/**
 * wps-settings.php 최소 안전판
 * - ABS_PATH 가 없으면 유추해서 정의
 * - wps-vars.php 존재 시 로드, 없으면 경고만 출력/기본값
 */
if (!defined('ABS_PATH')) {
    // /var/www/html/web 기준 상위가 /var/www/html
    define('ABS_PATH', dirname(__DIR__));
}

$vars = ABS_PATH . "/wps-vars.php";
if (is_file($vars)) {
    require_once $vars;
} else {
    // 필수 설정이 누락된 경우라도 페이지가 완전히 죽지 않도록 기본값 설정
    if (!defined('WPS_ENV')) define('WPS_ENV', 'dev');
    if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: 'mariadb');
    if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
    if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') ?: '');
    if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'herocomics');
}
