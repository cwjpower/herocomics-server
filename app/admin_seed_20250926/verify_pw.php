<?php
$d = json_decode(file_get_contents("/var/www/html/wps-users.json"), true);
$h = $d[0]["password_hash"] ?? "";
var_export(password_verify("admin123!", $h)); echo PHP_EOL;
