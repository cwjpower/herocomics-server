Set-Location $PSScriptRoot\..
docker compose build --no-cache
docker compose up -d
docker compose ps
