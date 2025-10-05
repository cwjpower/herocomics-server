<?php
declare(strict_types=1);
define("BANNER_DIR", "/var/www/html/uploads/banners"); // 서버 저장 경로
define("BANNER_URL", "/uploads/banners");              // 브라우저 경로
if (!is_dir(BANNER_DIR)) { @mkdir(BANNER_DIR, 0777, true); }
