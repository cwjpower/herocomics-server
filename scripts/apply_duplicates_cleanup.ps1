param(
  [Parameter(Mandatory=$false)][string]$CsvPath = "$PSScriptRoot\duplicates.csv",
  [Parameter(Mandatory=$false)][string]$Root    = "C:\herocomics\server",
  [switch]$DisableExtraConfs
)
$ErrorActionPreference = "Stop"
if(-not (Test-Path -LiteralPath $CsvPath)){ throw "CSV not found: $CsvPath" }
if(-not (Test-Path -LiteralPath $Root)){ throw "Root not found: $Root" }
$stamp = (Get-Date -Format "yyyyMMdd_HHmmss")
$Trash = Join-Path $Root ("_trash_apply_" + $stamp)
New-Item -ItemType Directory -Force $Trash | Out-Null
$Log   = Join-Path $Trash "actions.log"
$rows = Import-Csv -LiteralPath $CsvPath
[int]$moved=0; [int]$missing=0; [int]$errors=0
foreach($row in $rows){
  $dupRel = ($row.Duplicate -replace '^"+|"+$','')
  if([string]::IsNullOrWhiteSpace($dupRel)){ continue }
  $src = Join-Path $Root $dupRel
  if(Test-Path -LiteralPath $src){
    $dst = Join-Path $Trash $dupRel
    New-Item -ItemType Directory -Force (Split-Path $dst) | Out-Null
    try { Move-Item -LiteralPath $src -Destination $dst -Force; $moved++ }
    catch { $errors++ }
  } else { $missing++ }
}
if($DisableExtraConfs){
  $confDir = Join-Path $Root "docker\nginx\conf.d"
  if(Test-Path $confDir){
    $disabled = Join-Path $confDir ("_disabled_" + $stamp)
    New-Item -ItemType Directory -Force $disabled | Out-Null
    Get-ChildItem $confDir -Filter *.conf -File | Where-Object { $_.Name -ne "default.conf" } | ForEach-Object {
      Move-Item -LiteralPath $_.FullName -Destination (Join-Path $disabled $_.Name) -Force
    }
  }
}
Write-Host ("Moved={0}, Missing={1}, Errors={2}, Trash={3}" -f $moved,$missing,$errors,$Trash)
