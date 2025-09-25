<?php
declare(strict_types=1);

/**
 * functions-admin-logs.php (patched)
 * - 디렉터리 '/functions/'를 require/include 하던 문제를 수정
 * - 공용 로더를 명시적으로 불러와 의존 함수 확보
 */

require_once __DIR__ . '/functions-post.php';

/* admin logs 관련 함수가 있었다면 아래에 유지/추가하세요. */
