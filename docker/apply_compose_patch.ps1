
# Helper to apply the compose patch (Windows)
param(
  [string]$Yml = "C:\herocomics\server\docker\docker-compose.yml"
)

Write-Host "== Compose validate =="
docker compose -f $Yml config --services

Write-Host "== Up/Rebuild mariadb, php, nginx =="
docker compose -f $Yml up -d --build mariadb php nginx

Write-Host "== Running containers =="
docker ps --format "table {{.Names}}\t{{.Image}}\t{{.Ports}}"

Write-Host "== Quick check: php version =="
docker exec herocomics php -v

Write-Host "== If phpinfo missing, create and open =="
docker exec herocomics sh -lc 'printf "%s" "<?php phpinfo();" > /var/www/html/phpinfo.php'
Write-Host "Open: http://localhost:8081/phpinfo.php"
