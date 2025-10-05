$ErrorActionPreference="Stop"
$CONF_DIR="C:\herocomics\server\docker\nginx\conf.d"
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
Get-ChildItem $CONF_DIR -Filter *.conf | ForEach-Object {
  $path = $_.FullName
  $bytes = [System.IO.File]::ReadAllBytes($path)
  if ($bytes.Length -ge 3 -and $bytes[0] -eq 239 -and $bytes[1] -eq 187 -and $bytes[2] -eq 191) {
    $content = [System.Text.Encoding]::UTF8.GetString($bytes,3,$bytes.Length-3)
    [System.IO.File]::WriteAllText($path, $content, $utf8NoBom)
  }
}
