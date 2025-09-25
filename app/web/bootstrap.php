<?php
declare(strict_types=1);
if (!headers_sent()) { ob_start(); }
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__.'/../wps-config.php';
require_once __DIR__.'/wps-settings.php';
if (session_status() === PHP_SESSION_NONE) session_start();
