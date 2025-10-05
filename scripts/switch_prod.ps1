# PHP: display_errors Off
$phpini = @'
memory_limit=256M
upload_max_filesize=64M
post_max_size=64M
date.timezone=Asia/Seoul
display_errors=Off
log_errors=On
error_reporting=E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT
opcache.enable=1
'@
$enc = New-Object System.Text.UTF8Encoding($false)
[System.IO.File]::WriteAllText((Join-Path $PSScriptRoot '..\ops\php\php.ini'), $phpini, $enc)

# Nginx: add basic security headers
$ng = Get-Content (Join-Path $PSScriptRoot '..\ops\nginx\default.conf') -Raw
if ($ng -notmatch 'X-Content-Type-Options') {
  $ng = $ng -replace r'gzip_types([\s\S]*?)\}', r"gzip_types\1\n  add_header X-Content-Type-Options 'nosniff';\n  add_header X-Frame-Options 'SAMEORIGIN';\n  add_header Referrer-Policy 'strict-origin-when-cross-origin';\n}"
  [System.IO.File]::WriteAllText((Join-Path $PSScriptRoot '..\ops\nginx\default.conf'), $ng, $enc)
}
Set-Location $PSScriptRoot\..
docker compose up -d --force-recreate
Write-Host "Switched to production settings."
