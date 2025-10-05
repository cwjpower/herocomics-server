
param(
    [string]$Base = "C:\herocomics\server",
    [string]$ZipPath = "C:\Users\cwjpo\Downloads\Database.zip",
    [string]$SqlPath = "",
    [string]$Container = "hero-mariadb",
    [string]$RootPassword = "hero!db",
    [string]$DbName = "herocomics"
)

Write-Host "== HeroComics DB Import Helper v2 =="
Write-Host "Base: $Base"
Write-Host "ZipPath: $ZipPath"
if ($SqlPath) { Write-Host "SqlPath: $SqlPath" }
Write-Host "Container: $Container"
Write-Host "DbName: $DbName"
Write-Host ""

# Ensure directories
$null = New-Item -ItemType Directory -Force (Join-Path $Base "db") -ErrorAction SilentlyContinue
$unzDir = Join-Path $Base "db\_unz"
$null = New-Item -ItemType Directory -Force $unzDir -ErrorAction SilentlyContinue

# Resolve SQL file
$sqlFile = $null

# A) If explicit SqlPath provided, prefer it
if ($SqlPath) {
    if (Test-Path $SqlPath) {
        $sqlFile = Get-Item -Path $SqlPath -ErrorAction Stop
        Write-Host "Using explicit SqlPath: $($sqlFile.FullName)"
    } else {
        Write-Warning "SqlPath not found: $SqlPath"
    }
}

# B) If still not resolved, try unzipping
if (-not $sqlFile) {
    if (Test-Path $ZipPath) {
        try {
            Write-Host "Unzipping $ZipPath -> $unzDir ..."
            Expand-Archive -Path $ZipPath -DestinationPath $unzDir -Force
        } catch {
            Write-Warning "Expand-Archive failed: $($_.Exception.Message)"
        }
    } else {
        Write-Warning "ZIP not found: $ZipPath"
    }

    # C) Search common locations
    if (-not $sqlFile) {
        $lookDirs = @($unzDir, (Join-Path $Base "db"), "C:\Users\cwjpo\Downloads")
        try {
            $sqlFile = Get-ChildItem -Path $lookDirs -Filter *.sql -Recurse -ErrorAction SilentlyContinue |
                       Sort-Object Length -Descending | Select-Object -First 1
            if ($sqlFile) {
                Write-Host "Found SQL: $($sqlFile.FullName)"
            }
        } catch {
            # ignore
        }
    }
}

if (-not $sqlFile) {
    throw "No .sql file found. Provide -SqlPath or place a .sql file under: `n - $unzDir `n - $(Join-Path $Base 'db') `n - C:\Users\cwjpo\Downloads"
}

$target = Join-Path (Join-Path $Base "db") "herocomics_sql.sql"
Copy-Item $sqlFile.FullName $target -Force
Write-Host "SQL Source: $($sqlFile.FullName)"
Write-Host "SQL Target: $target"

# Check docker and container
try {
    $running = & docker ps --format "{{.Names}}"
    if (-not $running) {
        Write-Warning "docker ps returned empty. Is Docker Desktop running?"
    } elseif ($running -notmatch ("(^|\s)" + [regex]::Escape($Container) + "(\s|$)")) {
        Write-Warning "Container '$Container' not found. Running containers:`n$running"
        Write-Warning "If name differs, pass -Container with the correct name."
    }
} catch {
    Write-Warning "Failed to run 'docker ps': $($_.Exception.Message)"
}

# Copy SQL into container
Write-Host "Copying SQL into container..."
& docker cp $target "$Container`:/tmp/herocomics_sql.sql"
if ($LASTEXITCODE -ne 0) {
    throw "docker cp failed. Check container name or file path."
}

# Ensure DB exists
Write-Host "Ensuring database exists..."
& docker exec $Container mysql -uroot -p"$RootPassword" -e "CREATE DATABASE IF NOT EXISTS $DbName DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if ($LASTEXITCODE -ne 0) {
    throw "Failed to create database. Check MySQL root password/state."
}

# Import
Write-Host "Starting SQL import..."
$importCmd = "exec mysql -uroot -p`"$RootPassword`" $DbName < /tmp/herocomics_sql.sql"
& docker exec $Container sh -lc $importCmd
if ($LASTEXITCODE -ne 0) {
    throw "SQL import failed. Check file content and privileges."
}

# Quick checks
Write-Host ""
Write-Host "== Quick Checks =="
& docker exec $Container mysql -uroot -p"$RootPassword" -e "SHOW TABLES;" $DbName
& docker exec $Container mysql -uroot -p"$RootPassword" -e "SELECT COUNT(*) AS books FROM books;" $DbName 2>$null

Write-Host ""
Write-Host "Done."
