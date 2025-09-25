param([string]$IndexPath = "C:\herocomic\server\app\web\admin\index.php")

if (-not (Test-Path $IndexPath)) { Write-Error "index.php를 찾을 수 없습니다: $IndexPath"; exit 1 }

$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
$text = Get-Content -LiteralPath $IndexPath -Raw

# 이미 패치돼 있으면 스킵
if ($text -match "legacy-wps\.php") { Write-Host "이미 패치되어 있습니다."; exit 0 }

# 맨 위에 require 2줄 삽입
if ($text -match "^\s*<\?php") {
  $text = [regex]::Replace($text, "^\s*<\?php",
    "<?php`r`nrequire_once __DIR__ . '/../bootstrap.php';`r`nrequire_once __DIR__ . '/../lib/legacy-wps.php';", 1)
} else {
  $text = "<?php`r`nrequire_once __DIR__ . '/../bootstrap.php';`r`nrequire_once __DIR__ . '/../lib/legacy-wps.php';`r`n?>" + "`r`n" + $text
}

[System.IO.File]::WriteAllText($IndexPath, $text, $utf8NoBom)
Write-Host "admin/index.php 패치 완료."
