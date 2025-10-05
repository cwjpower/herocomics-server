<?php
require __DIR__."/functions.php";
if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }
if (!function_exists("wps_is_logged_in")) { function wps_is_logged_in(){ return !empty($_SESSION["user_id"]); } }
if (!wps_is_logged_in()) { header("Location: " . ADMIN_URL . "/login.php"); exit; }
if (!function_exists("page_header")) {
  function page_header($title=""){ echo "<!doctype html><html lang='ko'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>".htmlspecialchars($title,ENT_QUOTES|ENT_SUBSTITUTE,"UTF-8")."</title></head><body>"; }
}
if (!function_exists("page_footer")) { function page_footer(){ echo "</body></html>"; } }
page_header("??? ????");
$name = $_SESSION["user_name"] ?? "???";
echo "<style>body{font-family:system-ui,Segoe UI,Malgun Gothic,Apple SD Gothic Neo,Arial;padding:24px}</style>";
echo "<h1>?????, ".htmlspecialchars($name,ENT_QUOTES|ENT_SUBSTITUTE,"UTF-8")."</h1>";
echo "<p><a href='".ADMIN_URL."/logout.php'>????</a></p>";
page_footer();
