
# HerosComics Admin FIX Pack

## Files
- docker/docker-compose.nginx.addon.yml
- docker/nginx/conf.d/default.conf
- scripts/strip_bom.ps1
- scripts/clean_php.ps1
- scripts/install_admin.ps1
- scripts/restart.ps1
- admin/index.php, admin/phpinfo.php, admin/_health.php

## Use
1) Extract into `C:\herocomics\server\`
2) PowerShell:
   C:\herocomics\server\scripts\install_admin.ps1
   C:\herocomics\server\scripts\clean_php.ps1
   C:\herocomics\server\scripts\strip_bom.ps1
   docker compose -f C:\herocomics\server\docker\docker-compose.yml -f C:\herocomics\server\docker\docker-compose.nginx.addon.yml up -d --force-recreate nginx php
3) Open `http://localhost:8081/admin/` or `/admin/index.php` or `/admin/phpinfo.php`
