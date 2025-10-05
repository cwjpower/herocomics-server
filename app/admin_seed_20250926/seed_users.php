<?php
$ujson = "/var/www/html/wps-users.json";
$user  = "admin";
$pass  = "admin123!";
$hash  = password_hash($pass, PASSWORD_DEFAULT);
$payload = [["id"=>1,"username"=>$user,"role"=>"admin","password_hash"=>$hash]];
file_put_contents($ujson, json_encode($payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
echo "WROTE $ujson\n";
