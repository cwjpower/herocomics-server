HeroComics Backup
Timestamp : 20251001_174336
ZIP       : C:\herocomics\server\backups\hc_20251001_174336.zip
SHA256    : 0A7EE969AFDEBBEBCAC4AC015EDE444E24555999164DC2731DB492AE1ED3FCF3

포함물:
- DB dump          : backups\hc_20251001_174336\db\herocomics.sql (utf8mb4)
- 웹 코드          : app\web, app\tools
- 업로드 파일      : app\uploads
- Nginx 설정       : docker\nginx\conf.d
- Compose 파일     : docker\*.yml
- PHP 업로드 설정  : backups\hc_20251001_174336\misc\php_uploads.ini

퀵 복원:
1) docker compose -f "C:\herocomics\server\docker\docker-compose.yml" -f "C:\herocomics\server\docker\docker-compose.nginx.addon.yml" down
2) ZIP 압축 해제 → C:\herocomics\server (기존에 덮어쓰기)
3) DB 복원:
   Get-Content ".\backups\hc_20251001_174336\db\herocomics.sql" | 
     docker compose -f "C:\herocomics\server\docker\docker-compose.yml" -f "C:\herocomics\server\docker\docker-compose.nginx.addon.yml" exec -T mariadb sh -lc "mysql -u hero -psecret herocomics"
4) docker compose -f "C:\herocomics\server\docker\docker-compose.yml" -f "C:\herocomics\server\docker\docker-compose.nginx.addon.yml" up -d
5) http://localhost:8081/admin/login.php 접속 확인
