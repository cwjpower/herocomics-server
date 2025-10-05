param([string]$SrcAdminRoot)
if (-not (Test-Path $SrcAdminRoot)) { Write-Error "경로가 없습니다: $SrcAdminRoot"; exit 1 }
$dest = Join-Path $PSScriptRoot '..\app\web\admin' | Resolve-Path -ErrorAction Stop
New-Item -ItemType Directory -Force $dest.Path | Out-Null
robocopy $SrcAdminRoot $dest.Path /E
Write-Host "Copied admin to $($dest.Path)"
