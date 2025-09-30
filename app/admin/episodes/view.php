<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login();
header('Content-Type: text/html; charset=UTF-8');
$eid = (int)($_GET['id'] ?? 0);
$ep = $pdo->prepare("SELECT e.*, c.title AS comic_title FROM episode e JOIN comic c ON c.id=e.comic_id WHERE e.id=?");
$ep->execute([$eid]); $e=$ep->fetch();
if(!$e){ http_response_code(404); exit('not found'); }
$pages = $pdo->prepare("SELECT * FROM page WHERE episode_id=? ORDER BY page_no ASC"); $pages->execute([$eid]); $rows=$pages->fetchAll();
?><!doctype html><meta charset="utf-8"><title>뷰어</title>
<style>body{margin:0;font-family:system-ui} .wrap{max-width:980px;margin:0 auto}</style>
<div class="wrap">
  <h2>[<?=htmlspecialchars($e['comic_title'],ENT_QUOTES,'UTF-8')?>] <?=htmlspecialchars($e['title'],ENT_QUOTES,'UTF-8')?> (#<?=$e['id']?>)</h2>
  <?php foreach($rows as $r): ?>
    <p><img src="/<?=$r['file_path']?>" style="max-width:100%"></p>
  <?php endforeach; ?>
</div>
