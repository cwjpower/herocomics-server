$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
$files = @(
  'app/web/_dbcheck.php',
  'app/web/bootstrap.php',
  'app/web/wps-config.php',
  'app/web/lib/legacy-db.php',
  'app/web/index.php'
) | ForEach-Object { Join-Path $PSScriptRoot '..' $_ | Resolve-Path -ErrorAction SilentlyContinue }
foreach ($p in $files) {
  if (-not $p) { continue }
  $f = $p.Path
  $bytes = [System.IO.File]::ReadAllBytes($f)
  if ($bytes.Length -ge 3 -and $bytes[0] -eq 0xEF -and $bytes[1] -eq 0xBB -and $bytes[2] -eq 0xBF) {
    $bytes = $bytes[3..($bytes.Length-1)]
  }
  $text = [System.Text.Encoding]::UTF8.GetString($bytes)
  $text = [regex]::Replace($text, '^\s*<\?php', '<?php', 1)
  [System.IO.File]::WriteAllText($f, $text, $utf8NoBom)
}
Write-Host "Done. Files rewritten as UTF-8 (no BOM)."
