# MariaDB에 herocomics DB + hero 사용자 생성/권한 부여
$Sql = @'
CREATE DATABASE IF NOT EXISTS herocomics CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE USER IF NOT EXISTS 'hero'@'%' IDENTIFIED BY 'heropass';
GRANT ALL PRIVILEGES ON herocomics.* TO 'hero'@'%';
FLUSH PRIVILEGES;
'@

# 1) SQL을 파이프로 컨테이너 stdin으로 전달 (리다이렉션(<) 대신 파이프 사용)
$Sql | docker compose exec -T mariadb sh -lc 'mysql -u root -p"$MARIADB_ROOT_PASSWORD"'

if ($LASTEXITCODE -ne 0) {
  Write-Warning "파이프 방식 실패. docker cp 방식으로 재시도합니다."
  $tmp = Join-Path $PSScriptRoot "..\ops\db\_tmp_fix.sql"
  $utf8NoBom = New-Object System.Text.UTF8Encoding($false)
  [System.IO.File]::WriteAllText($tmp, $Sql, $utf8NoBom)

  # 2) 컨테이너 이름 조회 후 파일 복사 → 컨테이너 내부에서 실행
  $cid = (docker compose ps -q mariadb).Trim()
  if (-not $cid) { throw "mariadb 컨테이너를 찾지 못했습니다." }

  docker cp $tmp "$cid`:/tmp/_tmp_fix.sql" | Out-Null
  docker compose exec -T mariadb sh -lc 'mysql -u root -p"$MARIADB_ROOT_PASSWORD" < /tmp/_tmp_fix.sql'
}

Write-Host "DB/사용자 생성 스크립트 완료."
