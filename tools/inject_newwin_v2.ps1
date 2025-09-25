param(
  [string]$ProjectRoot = "C:\herocomic\server\app\web"
)
$adminAssets  = Join-Path $ProjectRoot "admin\assets"
$mobileAssets = Join-Path $ProjectRoot "mobile\assets"
New-Item -ItemType Directory -Force $adminAssets  | Out-Null
New-Item -ItemType Directory -Force $mobileAssets | Out-Null

# Put newwin.js in both admin/mobile
$here = Split-Path -Parent $MyInvocation.MyCommand.Path
Copy-Item -Path (Join-Path $here "newwin.js") -Destination (Join-Path $adminAssets "newwin.js") -Force
Copy-Item -Path (Join-Path $here "newwin.js") -Destination (Join-Path $mobileAssets "newwin.js") -Force

$srcHead  = '<script src="/admin/assets/newwin.js"></script>'
$srcBody  = '<script src="/admin/assets/newwin.js" defer></script>'

function Inject-File([string]$File) {
  if (-not (Test-Path $File)) { return "missing" }
  $s = Get-Content $File -Raw -ErrorAction SilentlyContinue
  if (-not $s) { return "empty" }
  if ($s -match [regex]::Escape('/admin/assets/newwin.js')) { return "exists" }

  # prefer injecting before </head>
  $patched = [regex]::Replace($s, "</head>", "$srcHead`n</head>", 1, 'IgnoreCase')
  if ($patched -ne $s) {
    Set-Content -Path $File -Value $patched -Encoding UTF8
    return "injected_head"
  }

  # fallback: inject before </body> with defer
  $patched2 = [regex]::Replace($s, "</body>", "$srcBody`n</body>", 1, 'IgnoreCase')
  if ($patched2 -ne $s) {
    Set-Content -Path $File -Value $patched2 -Encoding UTF8
    return "injected_body"
  }

  return "no_head_no_body"
}

# Target files (adjust as needed)
$targets = @(
  (Join-Path $ProjectRoot "admin\admin-header.php"),
  (Join-Path $ProjectRoot "admin\admin.php"),
  (Join-Path $ProjectRoot "mobile\index.php")
)
foreach($t in $targets) {
  "{0} : {1}" -f $t, (Inject-File $t)
}
