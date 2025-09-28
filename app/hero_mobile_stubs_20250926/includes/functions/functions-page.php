<?php
/**
 * Minimal page helpers (parity stub)
 * Path: /var/www/html/includes/functions/functions-page.php
 * Safe to keep in production until real implementation is restored.
 * No strict_types to avoid header issues in legacy code.
 */

if (!function_exists('e')) {
    function e($s) {
        return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('page_header')) {
    function page_header($title = '') {
        echo "<!doctype html><html lang='ko'><head><meta charset='utf-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
        echo "<title>" . e($title) . "</title>";
        echo "</head><body>";
    }
}

if (!function_exists('page_footer')) {
    function page_footer() {
        echo "</body></html>";
    }
}

if (!function_exists('redirect')) {
    function redirect($url, $code = 302) {
        if (!headers_sent()) {
            header("Location: " . $url, true, (int)$code);
        }
        exit;
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path = '') {
        $path = ltrim((string)$path, '/');
        return '/' . $path;
    }
}
