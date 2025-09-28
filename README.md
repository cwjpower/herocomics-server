# HeroComics Server

- Docker Compose (Nginx 1.27, PHP 8.2, MariaDB 11)
- App root: pp/ (웹 문서는 /app → 컨테이너 /var/www/html)
- 접속: http://127.0.0.1:8888/web/admin/

## Quick start
docker compose -f docker/docker-compose.yml up -d --build

