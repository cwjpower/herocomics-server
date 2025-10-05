param(
  [string]$WebRoot = "C:\herocomic\server\app\web"
)
$dir = Join-Path $WebRoot "functions"
if (-not (Test-Path $dir)) { throw "Not found: $dir" }

$enc = New-Object System.Text.UTF8Encoding($false)

$files = Get-ChildItem -Path $dir -Filter "functions-*.php" -File
foreach($f in $files){
  $raw = Get-Content $f.FullName -Raw -ErrorAction Stop
  # If it contains a bad require to /web/functions (directory), fix it
  if ($raw -match "require_once\s*\(\s*['\"].*/web/functions['\"].*\)") {
    $fixed = @"
<?php
declare(strict_types=1);
\$primary  = __DIR__ . '/functions.php';
\$fallback = __DIR__ . '/../includes/functions/functions.php';
if (is_file(\$primary)) {
    require_once \$primary;
} elseif (is_file(\$fallback)) {
    require_once \$fallback;
} else {
    throw new RuntimeException('functions.php not found. Checked: ' . \$primary . ', ' . \$fallback);
}
"@
    [System.IO.File]::WriteAllText($f.FullName, $fixed, $enc)
    Write-Host "[fixed] $($f.Name)"
  } else {
    Write-Host "[skip]  $($f.Name)"
  }
}
Write-Host "Done."
