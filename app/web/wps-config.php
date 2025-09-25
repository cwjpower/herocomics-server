<?php
declare(strict_types=1);
if (!defined('ABS_PATH')) define('ABS_PATH', __DIR__);
// /var/www/html/web/wps-config.php 로 둘 때는 ↓ 이렇게
require_once __DIR__ . '/wps-settings.php';   // ✅ web/ 가 더 이상 붙지 않도록 수정

/**
 * 경로 상수 정의
 * /var/www/html/web 기준 한 단계 위가 /var/www/html 이므로 dirname(__DIR__)
 */
if (!defined('ABS_PATH'))    define('ABS_PATH', dirname(__DIR__));
if (!defined('WEB_PATH'))    define('WEB_PATH', ABS_PATH . '/web');
if (!defined('INC_PATH'))    define('INC_PATH', WEB_PATH . '/inc');
if (!defined('FUNC_PATH'))   define('FUNC_PATH', WEB_PATH . '/functions');
if (!defined('MOBILE_PATH')) define('MOBILE_PATH', WEB_PATH . '/mobile');
if (!defined('ADMIN_PATH'))  define('ADMIN_PATH', WEB_PATH . '/admin');

/**
 * (선택) URL 상수: 필요할 때만 켜
 * BASE_URL은 nginx 포트와 루트에 맞춰 지정
 */
// if (!defined('BASE_URL'))   define('BASE_URL', 'http://localhost:8081');
// if (!defined('MOBILE_URL')) define('MOBILE_URL', BASE_URL . '/mobile');
// if (!defined('INC_URL'))    define('INC_URL', BASE_URL . '/inc');

// BASE_URL 자동 추론 (미정의 시)
if (!defined('BASE_URL')) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost:8081';
    define('BASE_URL', $scheme.'://'.$host);
}
if (!defined('MOBILE_URL')) define('MOBILE_URL', BASE_URL.'/mobile');
if (!defined('INC_URL'))    define('INC_URL',    BASE_URL.'/inc');

