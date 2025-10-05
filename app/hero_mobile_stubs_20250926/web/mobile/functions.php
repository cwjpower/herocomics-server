<?php
/**
 * Mobile helpers + include path bootstrapper (parity stub)
 * Path: /var/www/html/web/mobile/functions.php
 * Purpose: prevent fatal errors until real legacy functions are restored.
 */

// Resolve important roots based on this file's location
$__THIS_FILE = __FILE__;
$__MOBILE_DIR = dirname($__THIS_FILE);                     // /var/www/html/web/mobile
$__WEB_DIR    = dirname($__MOBILE_DIR);                    // /var/www/html/web
$__APP_ROOT   = dirname($__WEB_DIR);                       // /var/www/html

// Extend include_path so old relative includes keep working
$__paths = array_unique(array_filter([
    get_include_path(),
    $__APP_ROOT,
    $__WEB_DIR,
    $__WEB_DIR . '/functions',
    $__APP_ROOT . '/includes',
    $__APP_ROOT . '/includes/functions',
]));

set_include_path(implode(PATH_SEPARATOR, $__paths));

// Try to pull common page helpers if present
$__page_helpers = $__APP_ROOT . '/includes/functions/functions-page.php';
if (is_readable($__page_helpers)) {
    require_once $__page_helpers;
}

// ---- Mobile convenience shims ----
if (!function_exists('mobile_is_android')) {
    function mobile_is_android() {
        return (stripos($_SERVER['HTTP_USER_AGENT'] ?? '', 'Android') !== false);
    }
}

if (!function_exists('mobile_is_ios')) {
    function mobile_is_ios() {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return (stripos($ua, 'iPhone') !== false) || (stripos($ua, 'iPad') !== false);
    }
}

if (!function_exists('mobile_asset')) {
    function mobile_asset($path = '') {
        $path = ltrim((string)$path, '/');
        return '/mobile/' . $path;
    }
}

// You can safely add more legacy wrappers here as you rediscover old behaviors.
