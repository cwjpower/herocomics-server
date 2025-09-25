<?php
declare(strict_types=1);
ini_set('display_errors', '1');
error_reporting(E_ALL);

echo "<pre>[diag] mobile/_diag.php running\n";

$target = __DIR__ . '/../functions/functions-book.php';
echo "Including: $target\n";
require_once $target;
echo "OK: functions-book.php included.\n";

if (function_exists('hc_helper_loaded')) {
    echo "hc_helper_loaded(): " . (hc_helper_loaded() ? "true" : "false") . "\n";
} else {
    echo "hc_helper_loaded() not found. (This is fine if not needed.)\n";
}

echo "\nIncluded files:\n";
print_r(get_included_files());

echo "\nDone.</pre>";
