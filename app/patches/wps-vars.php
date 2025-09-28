<?php
// --- core paths ---
if (!defined('ABS_PATH'))   define('ABS_PATH',  '/var/www/html/');
if (!defined('WEB_PATH'))   define('WEB_PATH',  ABS_PATH.'web/');
if (!defined('MOBILE_PATH'))define('MOBILE_PATH', WEB_PATH.'mobile/');
if (!defined('ADMIN_PATH')) define('ADMIN_PATH',  WEB_PATH.'admin/');
if (!defined('INC_PATH'))   define('INC_PATH',    WEB_PATH.'inc/');

// --- URLs (??????/?? ???? ?? ??? ??) ---
if (!defined('BASE_URL'))   define('BASE_URL',   '/');
if (!defined('WEB_URL'))    define('WEB_URL',    BASE_URL.'web');
if (!defined('MOBILE_URL')) define('MOBILE_URL', WEB_URL.'/mobile');
if (!defined('ADMIN_URL'))  define('ADMIN_URL',  WEB_URL.'/admin');
if (!defined('INC_URL'))    define('INC_URL',    WEB_URL.'/inc');
if (!defined('ASSETS_URL')) define('ASSETS_URL', WEB_URL.'/assets');

// --- env ---
if (!defined('WPS_ENV'))    define('WPS_ENV','dev');
