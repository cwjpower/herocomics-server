# HeroComics Server Skeleton

## Quick Start
1. Copy your web sources into `app/web` (admin in `app/web/admin`).
2. Run:
   ```powershell
   .\scripts\up.ps1
   ```
3. Check:
   - http://localhost:8081/
   - http://localhost:8081/_dbcheck.php

## DB import
```powershell
.\scripts\db_import.ps1 C:\path	o\dump.sql
```

## Notes
- MariaDB mapped to host port **33061** to avoid conflicts.
- Nginx root is **/var/www/html/web** (i.e., `app/web` on host).
- PHP 8.2 with mysqli, pdo_mysql, mbstring, gd, zip, intl, opcache.
