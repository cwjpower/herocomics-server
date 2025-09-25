\
param([string]$OutDir = "$(Join-Path $env:USERPROFILE 'Desktop\herocomic_backups')")
New-Item -ItemType Directory -Force $OutDir | Out-Null
$ts = Get-Date -Format yyyyMMdd_HHmm
$dump = Join-Path $OutDir "db_herocomics_$ts.sql"
$appzip = Join-Path $OutDir "app_herocomics_$ts.zip"

# DB dump
docker compose exec -T mariadb sh -lc 'mysqldump -u root -p"$MARIADB_ROOT_PASSWORD" --databases herocomics' > $dump

# App zip
Compress-Archive -LiteralPath (Join-Path $PSScriptRoot '..\app') -DestinationPath $appzip -Force

Write-Host "Backups -> DB: $dump"
Write-Host "Backups -> APP: $appzip"
