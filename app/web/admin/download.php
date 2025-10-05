<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }
// TODO: 여기서 관리자 권한 체크를 붙이면 더 안전합니다.

$rel = trim((string)($_GET["file"] ?? ""), "/");
if ($rel === "" || str_contains($rel, "..") || str_starts_with($rel, ".")) {
  http_response_code(400); exit("bad request");
}
$base = "/var/www/html/uploads";
$baseReal = realpath($base);
$path = realpath($base . "/" . $rel);
if ($baseReal === false || $path === false || !str_starts_with($path, $baseReal) || !is_file($path)) {
  http_response_code(404); exit("not found");
}
$fname = basename($path);
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$fname."\"");
header("Content-Length: ".filesize($path));
header("X-Download-By: herocomics");
readfile($path);
