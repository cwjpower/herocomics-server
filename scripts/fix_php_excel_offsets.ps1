param([string]$ExcelDir="/var/www/html/web/includes/classes/PHPExcel")

# 타임스탬프는 PowerShell에서 만들고 문자열로 주입
$ts = Get-Date -Format "yyyyMMdd_HHmmss"

# 1) 백업
docker compose exec php sh -lc 'cd '"'"'$ExcelDir'"'"' && tar -czf '"'"'/tmp/PHPExcel_backup_'"$ts"'.tgz'"'"' . && ls -lh '"'"'/tmp/PHPExcel_backup_'"$ts"'.tgz'"'"''

# 2) PHP 패처 실행
docker compose exec php sh -lc 'php '"'"'/var/www/html/tools/fix_curly_offsets.php'"'"' '"'"'$ExcelDir'"'"''

# 3) 린트
docker compose exec php sh -lc 'find '"'"'$ExcelDir'"'"' -type f -name '"'"'*.php'"'"' -print0 | xargs -0 -I{} php -l '"'"'{}'"'"' | grep -v '"'"'No syntax errors'"'"' || true'

# 4) 재시작
docker compose restart php

Write-Host '[✓] Completed: fix_php_excel_offsets.ps1'
