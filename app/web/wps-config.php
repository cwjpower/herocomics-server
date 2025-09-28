<?php
declare(strict_types=1);
if (!defined('ABS_PATH'))   define('ABS_PATH', dirname(__DIR__) . '/');
if (!defined('WEB_PATH'))   define('WEB_PATH', __DIR__ . '/');
if (!defined('INC_PATH'))   define('INC_PATH', ABS_PATH . 'includes/');
if (!defined('FUNC_PATH'))  define('FUNC_PATH', INC_PATH . 'functions/');
if (!defined('MOBILE_PATH'))define('MOBILE_PATH', WEB_PATH . 'mobile/');
require_once ABS_PATH . 'wps-vars.php';
require_once WEB_PATH . 'bootstrap.php';