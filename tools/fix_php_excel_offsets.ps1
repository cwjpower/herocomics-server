# 파일: scripts/fix_php_excel_offsets.ps1
# 목적: PHPExcel 내 중괄호 오프셋(${var}{i}) → 대괄호(${var}[i]) 치환 + 백업 + 검증
# 사용: 프로젝트 루트(도커 compose 파일 있는 곳)에서 실행
#   PS> .\scripts\fix_php_excel_offsets.ps1

param(
  [string]$ExcelDir="/var/www/html/web/includes/classes/PHPExcel"
)

$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backup = "/tmp/PHPExcel_backup_$timestamp.tgz"

# 1) 백업
$cmd_backup = 'cd ''{0}'' && tar -czf ''{1}'' . && ls -lh ''{1}''' -f $ExcelDir, $backup
docker compose exec php sh -lc $cmd_backup

# 2) 패턴 치환 (변수{인덱스})
$cmd_var = "find ''{0}'' -type f -name ''*.php'' -print0 | xargs -0 -I{{}} sed -E -i 's/\$([A-Za-z_][A-Za-z0-9_]*)\{{([^}}]+)\}/\$\1[\2]/g' {{}}" -f $ExcelDir
docker compose exec php sh -lc $cmd_var

# 3) 패턴 치환 (객체프로퍼티{인덱스})
$cmd_prop = "find ''{0}'' -type f -name ''*.php'' -print0 | xargs -0 -I{{}} sed -E -i 's/(->[A-Za-z_][A-Za-z0-9_]*)\{{([^}}]+)\}/\1[\2]/g' {{}}" -f $ExcelDir
docker compose exec php sh -lc $cmd_prop

# 4) 문법 검사 (오류만 출력)
$cmd_lint = "find ''{0}'' -type f -name ''*.php'' -print0 | xargs -0 -I{{}} php -l '{{}}' | grep -v 'No syntax errors' || true" -f $ExcelDir
docker compose exec php sh -lc $cmd_lint

# 5) PHP 재시작
docker compose restart php

Write-Host "[✓] fix_php_excel_offsets.ps1 completed."
