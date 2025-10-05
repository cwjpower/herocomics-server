<?php
/* DEV ONLY: admin php downloader */
declare(strict_types=1);

$allow = [
  'banners.php'      => __DIR__ . '/banners.php',
  'db_browser.php'   => __DIR__ . '/db_browser.php',
  'episodes.php'     => __DIR__ . '/episodes.php',
  'episode_view.php' => __DIR__ . '/episode_view.php',
  'episode_edit.php' => __DIR__ . '/episode_edit.php',
  'episode_save.php' => __DIR__ . '/episode_save.php',
];

$f  = $_GET['f'] ?? '';
$bn = basename($f);
if (!isset($allow[$bn])) { http_response_code(404); exit('not allowed'); }
$path = $allow[$bn];
if (!is_file($path)) { http_response_code(404); exit('missing'); }

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$bn.'"');
header('Content-Length: '.filesize($path));
header('X-Download-By: herocomics');
readfile($path);