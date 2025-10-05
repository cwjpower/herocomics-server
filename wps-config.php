<?php
declare(strict_types=1);

/**
 * wps-config.php (minimal)
 * - 전역 상수/경로 정의 후, web/wps-settings.php를 통해 시스템 로드
 * - 중복 정의/재포함에 안전하도록 방어적 체크 적용
 */

if (!defined('ABS_PATH')) {
    define('ABS_PATH', __DIR__);
}

// web 루트의 설정 로더 포함
require_once __DIR__ . '/web/wps-settings.php';
