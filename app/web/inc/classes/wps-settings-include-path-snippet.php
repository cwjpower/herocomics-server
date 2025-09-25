<?php
// Place near the top of wps-settings.php (after loading wps-config.php)
set_include_path(
    get_include_path()
    . PATH_SEPARATOR . WEB_PATH . '/includes'
    . PATH_SEPARATOR . WEB_PATH . '/includes/classes'
    . PATH_SEPARATOR . WEB_PATH . '/includes/functions'
);
