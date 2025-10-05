$ErrorActionPreference="Stop"
$ROOT = "C:\herocomics\server\app\admin"
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
Get-ChildItem $ROOT -Recurse -Filter *.php | ForEach-Object {
  $p = $_.FullName
  $raw = [IO.File]::ReadAllBytes($p)
  if ($raw.Length -ge 3 -and $raw[0]-eq 239 -and $raw[1]-eq 187 -and $raw[2]-eq 191) { $raw = $raw[3..($raw.Length-1)] }
  $txt = [Text.Encoding]::UTF8.GetString($raw)
  $i = $txt.IndexOf("<?php")
  if ($i -gt 0) { $txt = $txt.Substring($i) }
  $txt = $txt -replace '\?>\s*$',''
  [IO.File]::WriteAllText($p, $txt, $utf8NoBom)
}
