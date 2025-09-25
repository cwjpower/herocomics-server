
param(
    [Parameter(Mandatory=$true)]
    [string]$RootPath
)

Write-Host "Scanning *.php under $RootPath ..." -ForegroundColor Cyan

# 백업/치환 로직
$files = Get-ChildItem -Path $RootPath -Recurse -Filter *.php -File
$patternList = @(
    # require_once('/var/www/html/web/functions/')
    '((require|include)(_once)?)\s*\(\s*([\'"])([^\'"]*?/web/functions/)\4\s*\)',
    # require_once(__DIR__ . '/functions/')
    '((require|include)(_once)?)\s*\(\s*__DIR__\s*\.\s*([\'"])/functions/\4\s*\)',
    # require_once('.../functions/')
    '((require|include)(_once)?)\s*\(\s*([\'"])(.*?/functions/)\4\s*\)'
)

foreach ($file in $files) {
    $text = Get-Content -Raw -LiteralPath $file.FullName
    $orig = $text

    foreach ($pat in $patternList) {
        $text = [regex]::Replace($text, $pat, { param($m)
            # $m.Groups[1] => require/include + _once
            # path 그룹은 5 또는 8 등 케이스에 따라 다름
            $prefix = $m.Groups[1].Value
            $quote  = if ($m.Groups[4].Success) { $m.Groups[4].Value } else { "'" }
            return "$prefix(" + $quote + ($m.Value -match '__DIR__' ? "__DIR__ . '/functions/functions-post.php'" : "functions/functions-post.php") + $quote + ")"
        })
    }

    if ($text -ne $orig) {
        Copy-Item -LiteralPath $file.FullName -Destination ($file.FullName + ".bak") -Force
        Set-Content -LiteralPath $file.FullName -Value $text -Encoding UTF8
        Write-Host ("Fixed: " + $file.FullName) -ForegroundColor Green
    }
}
Write-Host "Done." -ForegroundColor Cyan
