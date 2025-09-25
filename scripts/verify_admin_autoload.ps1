Set-Location $PSScriptRoot\..

# probe 파일 생성
$probe = 'app/web/admin/_probe.php'
$enc = New-Object System.Text.UTF8Encoding($false)
$code = @"
<?php
if (!function_exists('wps_exist_admin')) { echo 'NOFUNC'; exit; }
echo 'OK';
"@
[System.IO.File]::WriteAllText($probe, $code, $enc)

docker compose restart php | Out-Null
Start-Sleep -Seconds 2

try {
  $res = Invoke-WebRequest http://localhost:8081/admin/_probe.php -UseBasicParsing
  $content = $res.Content.Trim()
  if ($content -eq 'OK') { Write-Host 'Autoload OK (wps_exist_admin() available)' -ForegroundColor Green }
  else { Write-Warning "Autoload FAILED: $content" }
} catch { Write-Warning $_.Exception.Message }

Remove-Item $probe -Force -ErrorAction SilentlyContinue
