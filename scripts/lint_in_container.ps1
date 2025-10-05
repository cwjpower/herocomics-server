param(
  [string]$ComposeFile = "C:\herocomic\server\docker\docker-compose.yml"
)

$files = @(
  "/var/www/html/web/functions/functions-fancytree.php",
  "/var/www/html/web/admin/_preload.php",
  "/var/www/html/web/wps-settings.php",
  "/var/www/html/wps-vars.php",
  "/var/www/html/_dbcheck.php",
  "/var/www/html/_debug_login.php"
)

foreach ($f in $files) {
  Write-Host "php -l $f"
  & docker compose -f $ComposeFile exec php php -l $f
}
