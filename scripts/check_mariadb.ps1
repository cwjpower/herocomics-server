$BASE = "C:\herocomics\server"
$COMPOSE = Join-Path $BASE "docker\docker-compose.yml"

Write-Host "== Logs (last 200) =="
docker compose -f $COMPOSE logs mariadb --tail=200

Write-Host "\n== Health =="
docker inspect herocomics-mariadb-1 --format "{{json .State.Health }}" 2>$null

Write-Host "\n== SQL: SELECT VERSION() =="
docker compose -f $COMPOSE exec mariadb sh -lc "mariadb -uroot -p\"$MARIADB_ROOT_PASSWORD\" -N -s -e 'SELECT VERSION();'"