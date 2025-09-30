HeroComics Admin Setup Pack
Generated: 2025-09-28T09:53:19.427525

1) What this does
   - Copies your admin sources from "_tmp_extract\HerosComics_Admin-master" to "C:\herocomics\server\app\web\admin"
   - Patches NGINX to serve /admin -> /web/admin (with proper PHP alias handling)
   - Creates a stub "wps-vars.php" if it's missing (to avoid fatal missing-file includes)

2) How to use
   - Open PowerShell (Run as Administrator is recommended)
   - cd to the folder where you unzipped this pack
   - Run:
       .\install_admin.ps1
     or customize parameters:
       .\install_admin.ps1 -Base "C:\herocomics\server" -AdminSource "C:\herocomics\server\_tmp_extract\HerosComics_Admin-master" -Compose "C:\herocomics\server\docker\docker-compose.yml"

3) After running
   - Visit: http://localhost:8081/admin/   (fallback: http://localhost:8081/web/admin/)
   - If you see PHP warnings about wps-config.php / wps-settings.php / wps-vars.php:
       * Ensure these files exist in:
         C:\herocomics\server\app\wps-config.php
         C:\herocomics\server\app\web\wps-settings.php
         C:\herocomics\server\app\wps-vars.php
       * Edit "wps-vars.php" (created as a stub) with your real values.

4) Rollback
   - NGINX conf backup is created next to default.conf with a timestamp.
   - You can restore it manually and restart nginx:
       docker compose -f C:\herocomics\server\docker\docker-compose.yml restart nginx
