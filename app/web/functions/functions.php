<?php
declare(strict_types=1);

// includes/functions/functions.php

if (!function_exists('hc_ping')) {
    function hc_ping(): string { return "ok"; }
}

/**
 * 필요시 점진 추가할 헬퍼들 (예: db(), auth_check() 등)
 * - 파리티 유지: 기존 레거시 함수 시그니처/동작에 맞춰 최소 구현
 */

// 예시) db() 헬퍼(원한다면 사용) — mysqli 핸들 반환
if (!function_exists('db')) {
    function db(): mysqli {
        $host = defined('DB_HOST') ? DB_HOST : (getenv('DB_HOST') ?: 'mariadb');
        $user = defined('DB_USER') ? DB_USER : (getenv('DB_USER') ?: 'root');
        $pass = defined('DB_PASS') ? DB_PASS : (getenv('DB_PASS') ?: '');
        $db   = defined('DB_NAME') ? DB_NAME : (getenv('DB_NAME') ?: 'herocomics');
        $port = defined('DB_PORT') ? (int)DB_PORT : (int)(getenv('DB_PORT') ?: 3306);
        $mysqli = @new mysqli($host, $user, $pass, $db, $port);
        if ($mysqli->connect_errno) {
            throw new RuntimeException('DB connect error: '.$mysqli->connect_error);
        }
        return $mysqli;
    }
}
