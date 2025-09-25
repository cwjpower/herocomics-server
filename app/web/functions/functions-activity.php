<?php
declare(strict_types=1);

/**
 * functions-activity.php (patched)
 * - 기존에 '/functions/' 디렉터리를 require_once 하던 문제 수정
 * - 공용 로더를 명시적으로 불러와서 의존 함수들을 확보
 */

require_once __DIR__ . '/functions-post.php';

/* 여기에 activity 관련 함수/헬퍼가 있었다면 아래에 유지/추가하세요.
   예: function wps_log_activity(...) { ... }
*/
