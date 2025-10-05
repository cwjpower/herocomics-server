<?php
// ---- PATHS ----
if (!defined('ABS_PATH'))     define('ABS_PATH',     '/var/www/html/');
if (!defined('WEB_PATH'))     define('WEB_PATH',     ABS_PATH.'web/');
if (!defined('MOBILE_PATH'))  define('MOBILE_PATH',  WEB_PATH.'mobile/');
if (!defined('ADMIN_PATH'))   define('ADMIN_PATH',   WEB_PATH.'admin/');
if (!defined('INC_PATH'))     define('INC_PATH',     WEB_PATH.'inc/');

// ---- URLS (?? ?? ??) ----
if (!defined('BASE_URL'))     define('BASE_URL',     '/');
if (!defined('WEB_URL'))      define('WEB_URL',      BASE_URL.'web');
if (!defined('MOBILE_URL'))   define('MOBILE_URL',   WEB_URL.'/mobile');
if (!defined('ADMIN_URL'))    define('ADMIN_URL',    WEB_URL.'/admin');
if (!defined('INC_URL'))      define('INC_URL',      WEB_URL.'/inc');
if (!defined('ASSETS_URL'))   define('ASSETS_URL',   WEB_URL.'/assets');
if (!defined('CSS_URL'))      define('CSS_URL',      MOBILE_URL.'/css');
if (!defined('JS_URL'))       define('JS_URL',       MOBILE_URL.'/js');
if (!defined('IMG_URL'))      define('IMG_URL',      MOBILE_URL.'/img');

// ---- ENV & DEMO ----
if (!defined('WPS_ENV'))      define('WPS_ENV', 'dev');
// ?? ???? ??? ??(??? false? ??)
if (!defined('WPS_DEMO_DATA')) define('WPS_DEMO_DATA', true);
