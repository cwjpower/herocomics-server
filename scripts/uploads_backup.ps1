$ErrorActionPreference="Stop"
$BASE="C:\herocomics\server"
$SRC = "$BASE\app\uploads"
$OUT = "$BASE\backups"
New-Item -ItemType Directory -Force $OUT | Out-Null
$TS = Get-Date -Format "yyyyMMdd_HHmmss"
$ZIP = Join-Path $OUT ("uploads_" + $TS + ".zip")
Compress-Archive -Path "$SRC\*" -DestinationPath $ZIP -Force
