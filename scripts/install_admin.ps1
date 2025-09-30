$ErrorActionPreference="Stop"
$BASE="C:\herocomics\server"
$TARGET="$BASE\app\admin"
New-Item -ItemType Directory -Force $TARGET | Out-Null
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
function Write-NoBom($path,$content){ [IO.File]::WriteAllText($path,$content,$utf8NoBom) }
Write-Host "Created base admin structure at $TARGET"
