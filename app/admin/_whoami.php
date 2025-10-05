<?php
header('Content-Type: text/plain; charset=utf-8');

echo "== HEROCOMICS ADMIN DIAG ==\n";
echo "date: " . date('c') . "\n";
echo "php: " . PHP_VERSION . "\n";
echo "user: " . get_current_user() . "\n";
echo "cwd:  " . getcwd() . "\n";
echo "docroot: " . ($_SERVER['DOCUMENT_ROOT'] ?? '') . "\n";
echo "script:  " . ($_SERVER['SCRIPT_FILENAME'] ?? '') . "\n";
echo "session_id: " . (session_id() ?: '(none)') . "\n";

$paths = [
  __DIR__,
  dirname(__DIR__),
  dirname(__DIR__, 2),
];
foreach ($paths as $p) {
  echo "[check] $p: " . (is_dir($p) ? "ok\n" : "missing\n");
}
