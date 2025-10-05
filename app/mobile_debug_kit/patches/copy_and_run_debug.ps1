# copy_and_run_debug.ps1
$BASE = "C:\herocomics\server"
$LOCAL_DEBUG = "$BASE\debug"
New-Item -ItemType Directory -Force $LOCAL_DEBUG | Out-Null

docker compose -f "$BASE\docker\docker-compose.yml" cp "$BASE\app\mobile_debug_kit\web\mobile\_debug_run.php" php:/var/www/html/web/mobile/_debug_run.php
docker compose -f "$BASE\docker\docker-compose.yml" cp "$BASE\app\mobile_debug_kit\web\mobile\phpi.php"     php:/var/www/html/web/mobile/phpi.php

# ???? ???(???) + CLI ???(??)
try { Invoke-WebRequest -Uri "http://localhost:8081/web/mobile/_debug_run.php" -OutFile "$env:TEMP\_debug_run.html" | Out-Null } catch {}
docker compose -f "$BASE\docker\docker-compose.yml" exec php php /var/www/html/web/mobile/_debug_run.php | Out-Null

# ?? ??? ???? ??
docker compose -f "$BASE\docker\docker-compose.yml" cp php:/tmp/mobile_output.html     "$LOCAL_DEBUG\mobile_output.html"
docker compose -f "$BASE\docker\docker-compose.yml" cp php:/tmp/mobile_last_error.txt "$LOCAL_DEBUG\mobile_last_error.txt"
docker compose -f "$BASE\docker\docker-compose.yml" cp php:/tmp/mobile_headers.txt    "$LOCAL_DEBUG\mobile_headers.txt"

Write-Host "Dump copied to: $LOCAL_DEBUG"
