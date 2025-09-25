<?php
declare(strict_types=1);

/**
 * wps-settings.php
 * - 시스템 전역 설정 초기화 시, functions 디렉터리의 로더(functions-post.php)를 통해
 *   필요한 함수들을 안전하게 포함한다.
 */

require_once __DIR__ . '/functions/functions-post.php';
require_once __DIR__ . '/../includes/functions/functions-options.php'; // 실제 경로로 수정

