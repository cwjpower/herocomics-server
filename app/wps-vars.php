<?php
declare(strict_types=1);
if (!defined('ABS_PATH')) define('ABS_PATH', __DIR__);
if (!defined('WPS_ENV'))  define('WPS_ENV', 'dev');

if (!defined('DB_HOST')) define('DB_HOST', 'mariadb');    // ★ compose 서비스명
if (!defined('DB_USER')) define('DB_USER', 'hero');
if (!defined('DB_PASS')) define('DB_PASS', 'hero_pass');
if (!defined('DB_NAME')) define('DB_NAME', 'herocomics');
// if (!defined('DB_PORT')) define('DB_PORT', 3306);       // 기본 포트면 생략
