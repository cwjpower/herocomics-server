<?php
declare(strict_types=1);
/**
 * Fixed include: load the main functions.php (fallback to includes).
 */
$primary  = __DIR__ . '/functions.php';
$fallback = __DIR__ . '/../includes/functions/functions.php';

if (is_file($primary)) {
    require_once $primary;
} elseif (is_file($fallback)) {
    require_once $fallback;
} else {
    throw new RuntimeException('functions.php not found. Checked: ' . $primary . ', ' . $fallback);
}