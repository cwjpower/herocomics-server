$ErrorActionPreference="Stop"
$BASE="C:\herocomics\server"
Copy-Item "$BASE\docker\nginx\conf.d\default-public.conf" "$BASE\docker\nginx\conf.d\default.conf" -Force
Write-Host "Switched to public variant"
