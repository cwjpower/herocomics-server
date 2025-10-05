<?php
// ?? ?? ??
if (!function_exists("e")) { function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES|ENT_SUBSTITUTE, "UTF-8"); } }
if (!function_exists("page_header")) {
  function page_header($title=""){ echo "<!doctype html><html lang='ko'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>".e($title)."</title></head><body>"; }
}
if (!function_exists("page_footer")) { function page_footer(){ echo "</body></html>"; } }
