$ErrorActionPreference="Stop"
$BASE="C:\herocomics\server"
$COMPOSE="$BASE\docker\docker-compose.yml"
$NGADDON="$BASE\docker\docker-compose.nginx.addon.yml"
docker compose -f $COMPOSE -f $NGADDON run --rm nginx nginx -t
docker compose -f $COMPOSE -f $NGADDON up -d --force-recreate nginx php
docker compose -f $COMPOSE -f $NGADDON ps
