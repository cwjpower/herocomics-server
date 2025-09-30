<?php
<?php
// ===== HeroComics Parity Fallbacks =====
if (!defined('SITE_NAME'))  define('SITE_NAME', '히어로 코믹스');
if (!defined('BASE_URL'))   define('BASE_URL', 'http://localhost:8081');
if (!defined('ADMIN_URL'))  define('ADMIN_URL', BASE_URL . '/admin');
if (!defined('MOBILE_URL')) define('MOBILE_URL', BASE_URL . '/mobile');
// 과거 코드 호환(참조만 하면 됨)
if (!defined('BOOKTALK_PUBLISHER_ID')) define('BOOKTALK_PUBLISHER_ID', 1);