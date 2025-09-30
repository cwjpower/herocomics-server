
# Diagnosis & Fixes

- See `server_zip_report.txt` for what I found.
- Replace `C:\herocomics\server\docker\nginx\conf.d\default.conf` with `docker.nginx.conf.replacement.default.conf`.
- Run the two scripts to clean PHP BOM/whitespace and remove BOM from nginx confs.
- Then recreate nginx/php.

Sample:
  docker compose -f C:\herocomics\server\docker\docker-compose.yml -f C:\herocomics\server\docker\docker-compose.nginx.addon.yml up -d --force-recreate nginx php
