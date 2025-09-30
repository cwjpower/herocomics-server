param(
  [Parameter(Mandatory=$true)][string]$TrashRoot,
  [Parameter(Mandatory=$true)][string]$Root,
  [switch]$WhatIf,
  [string[]]$OnlyUnder
)
$ErrorActionPreference="Stop"
if(-not (Test-Path -LiteralPath $TrashRoot)){ throw "TrashRoot not found: $TrashRoot" }
if(-not (Test-Path -LiteralPath $Root)){ throw "Root not found: $Root" }
$stamp = (Get-Date -Format "yyyyMMdd_HHmmss")
$Log = Join-Path $TrashRoot ("restore_" + $stamp + ".log")
New-Item -ItemType File -Force $Log | Out-Null
[int]$restored=0; [int]$skipped=0; [int]$errors=0
$files = Get-ChildItem -LiteralPath $TrashRoot -Recurse -File -Force
foreach($f in $files){
  $rel = $f.FullName.Substring($TrashRoot.Length).TrimStart('\')
  if($OnlyUnder -and ($OnlyUnder | Where-Object { $rel.ToLower().StartsWith($_.ToLower().TrimStart('\')) }).Count -eq 0){ $skipped++; continue }
  $dst = Join-Path $Root $rel
  try{
    New-Item -ItemType Directory -Force (Split-Path $dst) | Out-Null
    if($WhatIf){
      ('WHATIF,"{0}","{1}"' -f $f.FullName,$dst) | Out-File -Append -FilePath $Log -Encoding UTF8
    } else {
      Move-Item -LiteralPath $f.FullName -Destination $dst -Force
      ('RESTORED,"{0}","{1}"' -f $f.FullName,$dst) | Out-File -Append -FilePath $Log -Encoding UTF8
      $restored++
    }
  }catch{ $errors++ }
}
Write-Host ("Restored={0}, Skipped={1}, Errors={2}" -f $restored,$skipped,$errors)
Write-Host ("Log: {0}" -f $Log)
