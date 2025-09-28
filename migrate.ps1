Param(
  [string]$Db = "herocomics",
  [string]$User = "hero",
  [string]$Pass = "heropass"
)

$migrationsDir = "C:\herocomics\server\migrations"

# 트래킹 테이블 준비
"CREATE TABLE IF NOT EXISTS schema_migrations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  filename VARCHAR(255) NOT NULL UNIQUE,
  checksum CHAR(40) NOT NULL,
  applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;" |
  docker exec -i server-mariadb-1 sh -lc `
  "mariadb --protocol=TCP -h127.0.0.1 -N -s -u$User -p$Pass $Db"; if ($LASTEXITCODE -ne 0) { throw "prepare failed" }

$files = Get-ChildItem -Path $migrationsDir -Filter *.sql | Sort-Object Name
foreach ($f in $files) {
  $name = $f.Name
  $sha1 = (Get-FileHash -Algorithm SHA1 $f.FullName).Hash.ToLower()

  $existing = (& {
    "SELECT checksum FROM schema_migrations WHERE filename='$name' LIMIT 1;" |
      docker exec -i server-mariadb-1 sh -lc `
      "mariadb --protocol=TCP -h127.0.0.1 -N -s -u$User -p$Pass $Db"
  })

  if ($existing -eq $sha1) { Write-Host "✓ $name (up-to-date)"; continue }

  Write-Host "→ Applying $name ..."
  Get-Content -Raw $f.FullName |
    docker exec -i server-mariadb-1 sh -lc `
    "mariadb --protocol=TCP -h127.0.0.1 -N -s -u$User -p$Pass $Db"
  if ($LASTEXITCODE -ne 0) { throw "Apply failed: $name" }

  $ins = "INSERT INTO schema_migrations (filename, checksum) VALUES ('$name', '$sha1')
          ON DUPLICATE KEY UPDATE checksum=VALUES(checksum), applied_at=CURRENT_TIMESTAMP;"
  $ins |
    docker exec -i server-mariadb-1 sh -lc `
    "mariadb --protocol=TCP -h127.0.0.1 -N -s -u$User -p$Pass $Db"
  if ($LASTEXITCODE -ne 0) { throw "Record failed: $name" }

  Write-Host "✓ Done: $name"
}
