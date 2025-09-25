<?php
/**
 * HeroComics web shim for legacy admin include
 * Allows ../wps-config.php includes from /web/admin/*.php
 */
$rootConfig = __DIR__ . '/../wps-config.php';
if (is_file($rootConfig)) {
    require_once $rootConfig;
    return;
}

http_response_code(500);
echo "HeroComics: missing root config: {$rootConfig}\n";
exit(1);
