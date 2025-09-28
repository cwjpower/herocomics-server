<?php
declare(strict_types=1);

/**
 * wps-settings.php 筌ㅼ뮇????됱읈?? * - ABS_PATH 揶쎛 ??곸몵筌??醫롰뀤??곴퐣 ?類ㅼ벥
 * - wps-vars.php 鈺곕똻????嚥≪뮆諭? ??곸몵筌?野껋럡?э쭕??곗뮆??疫꿸퀡??첎? */
if (!defined('ABS_PATH')) {define('ABS_PATH', dirname(__DIR__));
}

$vars = ABS_PATH . "/wps-vars.php";
if (is_file($vars)) {
    require_once $vars;
} else {
    // ?袁⑸땾 ??쇱젟???袁⑥뵭??野껋럩???곕즲 ??륁뵠筌왖揶쎛 ?袁⑹읈??雅뚯럩? ??낅즲嚥?疫꿸퀡??첎???쇱젟
    if (!defined('WPS_ENV')) define('WPS_ENV', 'dev');
    if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: 'mariadb');
    if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
    if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') ?: '');
    if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'herocomics');
}
