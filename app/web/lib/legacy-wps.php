<?php 
require_once __DIR__ . '/legacy-db.php';

function wps_redirect(string $url): void {
  if (!headers_sent()) { header('Location: ' . $url, true, 302); exit; }
  echo '<meta http-equiv="refresh" content="0;url=' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">'; exit;
}
function wps_exist_admin(): bool {
  $candidates = ['admins','admin','tb_admin','hc_admin','users_admin'];
  try {
    foreach ($candidates as $t) {
      $exists = db_get_var("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?", [$t]);
      if ($exists) {
        $count = db_get_var("SELECT COUNT(*) FROM `$t`");
        if (is_numeric($count) && intval($count) > 0) return true;
      }
    }
  } catch (Throwable $e) {}
  return false;
}