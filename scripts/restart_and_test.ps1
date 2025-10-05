$ErrorActionPreference="Stop"
$BASE="C:\herocomics\server"
$COMPOSE="$BASE\docker\docker-compose.yml"
$NGADDON="$BASE\docker\docker-compose.nginx.addon.yml"
docker compose -f $COMPOSE -f $NGADDON up -d --force-recreate nginx php
try {
  $r = Invoke-WebRequest -UseBasicParsing http://localhost:8081/admin/_health.php -TimeoutSec 10
  "{0} {1}" -f $r.StatusCode, ($r.Content.Trim())
} catch { Write-Warning $_ }
