$ErrorActionPreference="Stop"
$BASE="C:\herocomics\server"
$COMPOSE="$BASE\docker\docker-compose.yml"
$NGADDON="$BASE\docker\docker-compose.nginx.addon.yml"
$OUT="$BASE\backups"
New-Item -ItemType Directory -Force $OUT | Out-Null
$TS = Get-Date -Format "yyyyMMdd_HHmmss"
$DUMP = Join-Path $OUT ("db_herocomics_" + $TS + ".sql")
docker compose -f $COMPOSE -f $NGADDON exec -T mariadb mysqldump -uhero -psecret herocomics | Set-Content -LiteralPath $DUMP -Encoding UTF8
