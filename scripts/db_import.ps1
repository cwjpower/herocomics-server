param([string]$SqlPath)
if (-not (Test-Path $SqlPath)) { Write-Error "SQL 파일을 찾을 수 없습니다: $SqlPath"; exit 1 }
Set-Location $PSScriptRoot\..
docker compose exec -T mariadb sh -lc 'mysql -u root -p"$MARIADB_ROOT_PASSWORD" herocomics' < $SqlPath
