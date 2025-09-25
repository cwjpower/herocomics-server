Set-Location $PSScriptRoot\..
Write-Host "== docker compose ps ==" -ForegroundColor Cyan
docker compose ps
Write-Host "`n== Ports ==" -ForegroundColor Cyan
docker ps --format "table {{.Names}}\t{{.Ports}}\t{{.Status}}"
Write-Host "`n== Root / ==" -ForegroundColor Cyan
try { (Invoke-WebRequest http://localhost:8081/ -UseBasicParsing).StatusCode } catch { $_.Exception.Message }
Write-Host "`n== DB Check /_dbcheck.php ==" -ForegroundColor Cyan
try { (Invoke-WebRequest http://localhost:8081/_dbcheck.php -UseBasicParsing).Content } catch { $_.Exception.Message }
Write-Host "`n== Nginx last 60 lines ==" -ForegroundColor Cyan
docker compose logs nginx --tail 60
Write-Host "`n== PHP last 60 lines ==" -ForegroundColor Cyan
docker compose logs php --tail 60
Write-Host "`n== MariaDB last 60 lines ==" -ForegroundColor Cyan
docker compose logs mariadb --tail 60
