param(
  [string]$ComposeFile = "C:\herocomic\server\docker\docker-compose.yml"
)

function Get-PHPContainerId {
  $id = (& docker compose -f $ComposeFile ps -q php) 2>$null
  if (-not $id) {
    # fallback: 첫 번째 php 컨테이너
    $id = (& docker ps --filter "name=php" -q | Select-Object -First 1) 2>$null
  }
  return $id
}

$phpId = Get-PHPContainerId
if (-not $phpId) {
  Write-Error "php 컨테이너를 찾지 못했어. docker compose 서비스명이 php인지 확인해줘."
  exit 1
}

# 디렉토리 보장
& docker compose -f $ComposeFile exec php sh -lc 'mkdir -p /var/www/html/web/functions /var/www/html/web/admin' | Out-Null

# 백업 함수
function Backup-File($path) {
  & docker compose -f $ComposeFile exec php sh -lc "if [ -f '$path' ]; then cp '$path' '${path}.bak.$(date +%Y%m%d%H%M%S)'; fi"
}

# 복사 + 백업
$map = @{
  "patches\web\functions\functions-fancytree.php" = "/var/www/html/web/functions/functions-fancytree.php";
  "patches\web\admin\_preload.php"                = "/var/www/html/web/admin/_preload.php";
  "patches\web\wps-settings.php"                  = "/var/www/html/web/wps-settings.php";
  "patches\wps-vars.php"                          = "/var/www/html/wps-vars.php";
  "diagnostics\_dbcheck.php"                      = "/var/www/html/_dbcheck.php";
  "diagnostics\_debug_login.php"                  = "/var/www/html/_debug_login.php";
}

foreach ($k in $map.Keys) {
  $src = Join-Path $PSScriptRoot "..\$k"
  $dst = $map[$k]
  Write-Host "-> 백업: $dst"
  Backup-File $dst
  Write-Host "-> 복사: $src -> $dst"
  & docker compose -f $ComposeFile cp $src php:$dst
}

Write-Host "완료! 필요하면: docker compose -f $ComposeFile restart php nginx"
