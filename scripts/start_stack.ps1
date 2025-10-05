$ErrorActionPreference = "Stop"
$BASE = "C:\herocomics\server"
$COMPOSE = Join-Path $BASE "docker\docker-compose.yml"

# Ensure folders
New-Item -ItemType Directory -Force "$BASE\app" | Out-Null
New-Item -ItemType Directory -Force "$BASE\db\init" | Out-Null
New-Item -ItemType Directory -Force "$BASE\db\data" | Out-Null
New-Item -ItemType Directory -Force "$BASE\docker\nginx" | Out-Null
New-Item -ItemType Directory -Force "$BASE\docker\php82" | Out-Null

# Seed example files if missing
if (-not (Test-Path "$BASE\docker\.env")) {
  Copy-Item "$BASE\docker\.env.sample" "$BASE\docker\.env" -Force
}
if (-not (Test-Path "$BASE\app\_dbcheck.php")) {
  Copy-Item "$BASE\app\_dbcheck.php.sample" "$BASE\app\_dbcheck.php" -Force
}

docker compose -f $COMPOSE up -d --build
docker compose -f $COMPOSE ps