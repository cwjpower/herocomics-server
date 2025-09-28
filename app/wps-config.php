<?php
define('DB_HOST', getenv('DB_HOST') ?: 'mariadb');
define('DB_PORT', getenv('DB_PORT') ?: 3306);
define('DB_NAME', getenv('MARIADB_DATABASE') ?: 'herocomics');
define('DB_USER', getenv('MARIADB_USER') ?: 'hero');
define('DB_PASS', getenv('MARIADB_PASSWORD') ?: 'heropass');
