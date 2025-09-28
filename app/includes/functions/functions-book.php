<?php
declare(strict_types=1);

/**
 * functions-book.php (patched)
 * - 기존에 디렉터리('/functions/')를 require_once 하던 문제를 수정.
 * - 항상 현재 폴더 기준 로더를 포함한다.
 */

// 필수: functions 로더
require_once __DIR__ . '/functions-post.php';

/* 
 * 필요 시, 여기에 '도서' 관련 함수들을 추가/유지하세요.
 * 예: getBookById(), listBooks(), etc.
 */
