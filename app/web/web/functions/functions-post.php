<?php
declare(strict_types=1);
/**
 * functions-post.php (ALL-IN LOADER by Charlie)
 * - 목적: web/functions 폴더 내 모든 .php를 자동 로드하여 함수 누락을 방지
 * - 안전장치: 자기 자신/이미 로드한 파일 제외
 * - 우선순위 파일은 bootstrap 리스트에 먼저 나열
 */

$bootstrapList = [
    __DIR__ . '/functions-scripts.php',
    // __DIR__ . '/functions-options.php', // wps_get_option()이 여기에 있다면 주석 해제
];

foreach ($bootstrapList as $bootstrapFile) {
    if (is_file($bootstrapFile)) {
        require_once $bootstrapFile;
    }
}

$loaded = array_map('realpath', array_filter($bootstrapList, 'is_file'));
$self   = realpath(__FILE__);

foreach (glob(__DIR__ . '/*.php') as $file) {
    $real = realpath($file);
    if ($real === $self) continue;                // 자기 자신 제외
    if (in_array($real, $loaded, true)) continue; // 이미 로드한 우선순위 파일 제외
    require_once $file;
}
