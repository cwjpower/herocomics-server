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

// Try to pull WPS option helpers if present; otherwise shim minimal functions
$__wps_helpers = $__APP_ROOT . '/includes/functions/functions-wps.php';
if (is_readable($__wps_helpers)) {
    require_once $__wps_helpers;
} else {
    if (!function_exists('wps_get_option')) {
        function wps_get_option($key, $default = null) { return $default; }
    }
    if (!function_exists('wps_set_option')) {
        function wps_set_option($key, $value) { return true; }
    }
    if (!function_exists('wps_is_enabled')) {
        function wps_is_enabled($value, $default = false) {
            if ($value === null) return (bool)$default;
            $v = is_string($value) ? strtolower(trim($value)) : $value;
            if (is_bool($v)) return $v;
            return in_array($v, ['1','true','yes','y','on'], true);
        }
    }
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
