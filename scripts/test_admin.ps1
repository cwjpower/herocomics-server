# Quick test for admin health
$BASE    = "C:\herocomics\server"
$COMPOSE = "$BASE\docker\docker-compose.yml"
$NGADDON = "$BASE\docker\docker-compose.nginx.addon.yml"

docker compose -f $COMPOSE -f $NGADDON restart nginx | Out-Null
Start-Sleep -Seconds 2

try {
  $res = Invoke-WebRequest -UseBasicParsing http://localhost:8081/admin/_health.php -TimeoutSec 10
  $status = $res.StatusCode
  $content = $res.Content.Trim()
  Write-Host ("Status: {0}" -f $status)
  Write-Host ("Body  : {0}" -f $content)
} catch {
  Write-Error $_
}
