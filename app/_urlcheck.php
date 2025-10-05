<?php
/**
 * URL & path diagnostic helper (drop-in)
 * Can live at either /var/www/html/_urlcheck.php or /var/www/html/web/_urlcheck.php
 */
header('Content-Type: text/html; charset=utf-8');
function h($s){return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');}
$doc = $_SERVER['DOCUMENT_ROOT'] ?? '';
$real = realpath(__DIR__) ?: __DIR__;
$uri  = $_SERVER['REQUEST_URI'] ?? '';
$host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
if (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') $scheme = 'https';
$port = $_SERVER['HTTP_X_FORWARDED_PORT'] ?? ($_SERVER['SERVER_PORT'] ?? '');
$base = $scheme . '://' . $host;
if ($port && !in_array((int)$port, [80,443], true) && strpos($host, ':')===false) $base .= ':' . $port;
$candidates = ['/mobile/index.php', '/web/mobile/index.php', '/mobile/', '/web/mobile/'];
$mobile = null;
foreach ($candidates as $rel) {
  $fs = rtrim(str_replace('\\','/', $doc), '/') . $rel;
  if ((substr($rel,-1)==='/' && is_dir($fs)) || (substr($rel,-1)!=='/' && is_file($fs))) { $mobile = $rel; break; }
}
?>
<!doctype html><html lang="ko"><meta charset="utf-8"><title>URL 체크 (dual-root)</title>
<style>body{font-family:ui-sans-serif,system-ui,Segoe UI,Roboto,"Apple SD Gothic Neo","Malgun Gothic",sans-serif;padding:24px;line-height:1.5}
table{border-collapse:collapse;width:100%}th,td{border:1px solid #ccc;padding:8px;text-align:left;font-size:14px}a.btn{display:inline-block;padding:10px 14px;border:1px solid #888;border-radius:8px;text-decoration:none;margin-right:8px}</style>
<h1>URL 체크 (dual-root)</h1>
<p>
  <a class="btn" href="<?=h($base)?>/" target="_blank">사이트 루트</a>
  <?php if ($mobile): ?><a class="btn" href="<?=h($base.$mobile)?>" target="_blank">모바일 인덱스 (<?=h($mobile)?>)</a><?php endif; ?>
</p>
<table>
<tr><th>DOCUMENT_ROOT</th><td><code><?=h($doc)?></code></td></tr>
<tr><th>__DIR__</th><td><code><?=h($real)?></code></td></tr>
<tr><th>REQUEST_URI</th><td><code><?=h($uri)?></code></td></tr>
<tr><th>베이스 URL</th><td><code><?=h($base)?></code></td></tr>
<tr><th>X-Forwarded-Proto</th><td><code><?=h($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')?></code></td></tr>
<tr><th>HTTP_HOST</th><td><code><?=h($_SERVER['HTTP_HOST'] ?? '')?></code></td></tr>
<tr><th>SERVER_NAME</th><td><code><?=h($_SERVER['SERVER_NAME'] ?? '')?></code></td></tr>
<tr><th>SERVER_PORT</th><td><code><?=h($_SERVER['SERVER_PORT'] ?? '')?></code></td></tr>
<tr><th>추정 모바일 경로</th><td><code><?=h($mobile ?? '(없음)')?></code></td></tr>
</table>
