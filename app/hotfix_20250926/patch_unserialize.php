<?php
$idx = "/var/www/html/web/mobile/index.php";
if (is_file($idx)) {
  $c = file_get_contents($idx);
  if ($c!==false) {
    $new = preg_replace("/unserialize\s*\(/", "unserialize((string)", $c);
    if ($new!==null && $new!==$c) { file_put_contents($idx, $new); echo "[PATCH] unserialize cast\n"; }
    else { echo "[OK] unserialize untouched\n"; }
  }
}
