<?php
require "/var/www/html/web/mobile/functions.php";
echo "has lps_get_posts_by_type: ", (function_exists("lps_get_posts_by_type")?"YES":"NO"), "\n";
echo "has wps_get_current_user_id: ", (function_exists("wps_get_current_user_id")?"YES":"NO"), "\n";
echo "MOBILE_URL: ", (defined("MOBILE_URL")?MOBILE_URL:"(NG)"), "\n";
