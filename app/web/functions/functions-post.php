<?php
declare(strict_types=1);
/**
 * functions-post.php (ALL-IN LOADER)
 * - 목적: web/functions 폴더 내 모든 .php를 로드하여, wps_get_option 같은 함수 누락을 방지
 * - 원칙: 우선순위가 필요한 파일은 bootstrap 리스트에 먼저 명시 → 이후 나머지 전체를 로드
 * - 안전장치: 자기 자신 및 이미 로드한 파일은 스킵
 */

// 1) 우선순위 파일들(필요 시 여기에 파일명을 추가)
$bootstrapList = [
    __DIR__ . '/functions-scripts.php',     // 공통 헬퍼(있으면 먼저)
    // __DIR__ . '/functions-options.php',  // wps_get_option()이 여기에 있다면 주석 해제
];

foreach ($bootstrapList as $bootstrapFile) {
    if (is_file($bootstrapFile)) {
        require_once $bootstrapFile;
    }
}

// 2) 폴더 내 나머지 .php 전체 로드(자기 자신/중복 제외)
$loaded = array_map('realpath', array_filter($bootstrapList, 'is_file'));
$self   = realpath(__FILE__);

foreach (glob(__DIR__ . '/*.php') as $file) {
    $real = realpath($file);
    if ($real === $self) {
        continue; // 자기 자신 제외
    }
    if (in_array($real, $loaded, true)) {
        continue; // 이미 로드한 우선순위 파일 제외
    }
    require_once $file;
}
