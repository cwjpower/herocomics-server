# HeroComics PHP Hotfix Stubs — 2025-09-26

## What’s inside
- web/functions/functions-fancytree.php — minimal include-only shim
- web/functions/functions-post.php — empty stub
- includes/functions/functions.php — core aggregator stub
- wps-config.safe.sample.php — reference template

## How to deploy (PowerShell on Windows)

# Paths
$BASE = "C:\herocomic\server"
$DCY  = "$BASE\docker\docker-compose.yml"

# Copy stubs into container (adjust service names if needed)
docker compose -f $DCY cp web/functions/functions-fancytree.php php:/var/www/html/web/functions/functions-fancytree.php
docker compose -f $DCY cp web/functions/functions-post.php      php:/var/www/html/web/functions/functions-post.php
docker compose -f $DCY cp includes/functions/functions.php      php:/var/www/html/includes/functions/functions.php

# Lint to confirm syntax OK
docker compose -f $DCY exec php php -l /var/www/html/web/functions/functions-fancytree.php
docker compose -f $DCY exec php php -l /var/www/html/web/functions/functions-post.php
docker compose -f $DCY exec php php -l /var/www/html/includes/functions/functions.php

# Restart services
docker compose -f $DCY restart php
docker compose -f $DCY restart nginx

## How to deploy (inside container)

cp /mnt/shared/web/functions/functions-fancytree.php /var/www/html/web/functions/
cp /mnt/shared/web/functions/functions-post.php      /var/www/html/web/functions/
mkdir -p /var/www/html/includes/functions
cp /mnt/shared/includes/functions/functions.php      /var/www/html/includes/functions/
php -l /var/www/html/web/functions/functions-fancytree.php && php -l /var/www/html/web/functions/functions-post.php && php -l /var/www/html/includes/functions/functions.php

## Verification
- Hit http://localhost:8081/admin/login.php
- Check PHP-FPM logs: docker compose -f $DCY logs php --tail=200
