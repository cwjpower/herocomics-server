
param(
    [string]$Base = "C:\herocomics\server",
    [string]$ZipPath = "C:\Users\cwjpo\Downloads\Database.zip",
    [string]$Container = "hero-mariadb",
    [string]$RootPassword = "hero!db",
    [string]$DbName = "herocomics"
)

Write-Host "== HeroComics DB Import Helper =="
Write-Host "Base: $Base"
Write-Host "ZipPath: $ZipPath"
Write-Host "Container: $Container"
Write-Host "DbName: $DbName"
Write-Host ""

# 0) Ensure directories
$null = New-Item -ItemType Directory -Force (Join-Path $Base "db") -ErrorAction SilentlyContinue
$unzDir = Join-Path $Base "db\_unz"
$null = New-Item -ItemType Directory -Force $unzDir -ErrorAction SilentlyContinue

# 1) Unzip if ZIP exists
if (Test-Path $ZipPath) {
    try {
        Write-Host "Unzipping $ZipPath -> $unzDir ..."
        Expand-Archive -Path $ZipPath -DestinationPath $unzDir -Force
    } catch {
        Write-Warning "Expand-Archive 실패: $($_.Exception.Message)"
    }
} else {
    Write-Warning "ZIP 파일이 없거나 경로가 잘못됨: $ZipPath"
}

# 2) Try to find an .sql file
$lookDirs = @($unzDir, (Join-Path $Base "db"), "C:\Users\cwjpo\Downloads")
$sqlFile = $null
try {
    $sqlFile = Get-ChildItem -Path $lookDirs -Filter *.sql -Recurse -ErrorAction SilentlyContinue |
               Sort-Object Length -Descending | Select-Object -First 1
} catch {
    # ignore
}

if (-not $sqlFile) {
    throw "SQL 파일을 찾을 수 없어. 다음 위치 중 하나에 .sql 파일을 둔 뒤 다시 실행해줘: `n - $unzDir `n - $(Join-Path $Base 'db') `n - C:\Users\cwjpo\Downloads"
}

$target = Join-Path (Join-Path $Base "db") "herocomics_sql.sql"
Copy-Item $sqlFile.FullName $target -Force
Write-Host "SQL 원본: $($sqlFile.FullName)"
Write-Host "SQL 대상: $target"

# 3) Docker container check
try {
    $running = & docker ps --format "{{.Names}}"
    if (-not $running) {
        Write-Warning "docker ps 결과가 비어있어. Docker Desktop이 켜져 있는지 확인해줘."
    } elseif ($running -notmatch ("(^|\s)" + [regex]::Escape($Container) + "(\s|$)")) {
        Write-Warning "컨테이너 '$Container' 를 찾지 못했어. 현재 실행 중: `n$running"
        Write-Warning "컨테이너 이름이 다르면 -Container 파라미터로 올바른 이름을 넘겨줘."
    }
} catch {
    Write-Warning "docker ps 실행 실패: $($_.Exception.Message)"
}

# 4) Copy SQL into container
Write-Host "컨테이너로 SQL 복사 중..."
& docker cp $target "$Container`:/tmp/herocomics_sql.sql"
if ($LASTEXITCODE -ne 0) {
    throw "docker cp 실패. 컨테이너 이름 혹은 파일 경로를 확인해줘."
}

# 5) Ensure DB exists
Write-Host "DB 생성 확인 중..."
& docker exec $Container mysql -uroot -p"$RootPassword" -e "CREATE DATABASE IF NOT EXISTS $DbName DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if ($LASTEXITCODE -ne 0) {
    throw "DB 생성 실패. root 비밀번호 혹은 MySQL 상태를 확인해줘."
}

# 6) Import (needs shell for input redirection)
Write-Host "SQL Import 시작... (잠시 걸릴 수 있음)"
$importCmd = "exec mysql -uroot -p`"$RootPassword`" $DbName < /tmp/herocomics_sql.sql"
& docker exec $Container sh -lc $importCmd
if ($LASTEXITCODE -ne 0) {
    throw "SQL Import 실패. 파일 내용 혹은 DB 권한을 확인해줘."
}

# 7) Quick checks
Write-Host ""
Write-Host "== 간단 검증 =="
& docker exec $Container mysql -uroot -p"$RootPassword" -e "SHOW TABLES;" $DbName
& docker exec $Container mysql -uroot -p"$RootPassword" -e "SELECT COUNT(*) AS books FROM books;" $DbName 2>$null

Write-Host ""
Write-Host "완료! 문제가 있으면 위의 경고/오류 메시지를 알려줘."
