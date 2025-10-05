#!/usr/bin/env bash
# 파일: scripts/fix_php_excel_offsets.sh
# 사용법:
#   (호스트)  bash scripts/fix_php_excel_offsets.sh
#   (컨테이너) sh   scripts/fix_php_excel_offsets.sh

set -euo pipefail

EXCEL_DIR="${EXCEL_DIR:-/var/www/html/web/includes/classes/PHPExcel}"
BACKUP="/tmp/PHPExcel_backup_$(date +%Y%m%d_%H%M%S).tgz"

in_container() {
  [[ -d /usr/local/etc/php ]]
}

if in_container; then
  echo "[*] Running inside container"
  (cd "$EXCEL_DIR" && tar -czf "$BACKUP" . && ls -lh "$BACKUP")
  find "$EXCEL_DIR" -type f -name "*.php" -print0 | xargs -0 -I{} sed -E -i 's/\$([A-Za-z_][A-Za-z0-9_]*)\{([^}]+)\}/\$\1[\2]/g' {}
  find "$EXCEL_DIR" -type f -name "*.php" -print0 | xargs -0 -I{} sed -E -i 's/(->[A-Za-z_][A-Za-z0-9_]*)\{([^}]+)\}/\1[\2]/g' {}
  find "$EXCEL_DIR" -type f -name "*.php" -print0 | xargs -0 -I{} php -l "{}" | grep -v "No syntax errors" || true
else
  echo "[*] Running on host via docker compose exec"
  docker compose exec php sh -lc "cd '$EXCEL_DIR' && tar -czf '$BACKUP' . && ls -lh '$BACKUP'"
  docker compose exec php sh -lc "find '$EXCEL_DIR' -type f -name '*.php' -print0 | xargs -0 -I{} sed -E -i 's/\\$([A-Za-z_][A-Za-z0-9_]*)\\{([^}]+)\\}/\\$\\1[\\2]/g' {}"
  docker compose exec php sh -lc "find '$EXCEL_DIR' -type f -name '*.php' -print0 | xargs -0 -I{} sed -E -i 's/(->[A-Za-z_][A-Za-z0-9_]*)\\{([^}]+)\\}/\\1[\\2]/g' {}"
  docker compose exec php sh -lc "find '$EXCEL_DIR' -type f -name '*.php' -print0 | xargs -0 -I{} php -l '{}' | grep -v 'No syntax errors' || true"
  docker compose restart php
fi

echo "[✓] Done."
