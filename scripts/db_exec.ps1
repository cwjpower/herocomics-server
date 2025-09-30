param([Parameter(Mandatory=$true)][string]$Sql)
$BASE = "C:\herocomics\server"
$COMPOSE = Join-Path $BASE "docker\docker-compose.yml"

$escaped = $Sql.Replace('"', '\"').Replace('`', '``')
docker compose -f $COMPOSE exec mariadb sh -lc "mariadb -uroot -p\"$MARIADB_ROOT_PASSWORD\" -N -s -e \"$escaped\""