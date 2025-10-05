\
# 업로드/캐시 디렉터리 생성 및 권한(컨테이너 내부 사용자 www-data) 부여
$dirs = @(
  "/var/www/html/web/assets/uploads",
  "/var/www/html/web/assets/cache",
  "/var/www/html/web/publish",
  "/var/www/html/web/mobile/uploads"
)
$cmd = "set -e; " + (" && ".join([f"mkdir -p {d} && chown -R www-data:www-data {d}" for d in [
  "/var/www/html/web/assets/uploads",
  "/var/www/html/web/assets/cache",
  "/var/www/html/web/publish",
  "/var/www/html/web/mobile/uploads"
]]))
docker compose exec -T php sh -lc "$cmd"
Write-Host "Permissions set for upload/cache directories."
