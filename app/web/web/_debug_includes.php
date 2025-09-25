<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

echo "<pre>_debug_includes.php running...\n";
echo "include_path=" . get_include_path() . "\n";
echo "cwd=" . getcwd() . "\n";
echo "__FILE__=" . __FILE__ . "\n\n";

// 테스트: 필수 로더 포함
$target = __DIR__ . '/functions/functions-post.php';
echo "Trying require_once $target ...\n";
require_once $target;
echo "OK: functions-post.php included.\n";

// 포함된 파일 목록 출력
echo "\nIncluded files:\n";
print_r(get_included_files());

echo "\nDone.</pre>";
