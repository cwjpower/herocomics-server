<?php
declare(strict_types=1);
$env = getenv('APP_ENV') ?: 'development';
session_set_cookie_params([
  'httponly' => true,
  'samesite' => 'Lax',
  'secure'   => $env === 'production',
]);
session_start();
require_once __DIR__.'/config.php';
function csrf_token(): string { if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(16)); } return $_SESSION['csrf']; }
function csrf_check(): void { if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) { http_response_code(403); exit('CSRF invalid'); } }
function require_login(): void { if (empty($_SESSION['admin_id'])) { header('Location: /admin/login.php'); exit; } }
function flash(string $key, ?string $val=null): ?string { if ($val !== null) { $_SESSION['__flash'][$key] = $val; return null; } $v = $_SESSION['__flash'][$key] ?? null; unset($_SESSION['__flash'][$key]); return $v; }
