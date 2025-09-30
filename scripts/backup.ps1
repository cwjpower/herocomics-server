$ErrorActionPreference='Stop'
$BASE='C:\herocomics\server'
$COMPOSE="C:\herocomics\server\docker\docker-compose.yml"
$NGADDON="C:\herocomics\server\docker\docker-compose.nginx.addon.yml"
$DEST=(Get-ChildItem "C:\herocomics\server\app" -Directory -Recurse | ?{ Test-Path (Join-Path $_ 'web\index.php') } | select -Expand FullName -First 1)
$bk="C:\herocomics\server\backups"; New-Item -ItemType Directory -Force $bk | Out-Null
$stamp=Get-Date -Format yyyyMMdd_HHmmss
Compress-Archive -Path $DEST -DestinationPath (Join-Path $bk "app_$stamp.zip")
docker compose -f $COMPOSE -f $NGADDON exec mariadb sh -lc "mysqldump -uhero -psecret --single-transaction --quick --routines --events herocomics" > (Join-Path $bk "db_$stamp.sql")
