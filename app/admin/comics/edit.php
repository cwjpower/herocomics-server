<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login();
header('Content-Type: text/html; charset=UTF-8');
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM comic WHERE id=?');
$stmt->execute([$id]);
$item = $stmt->fetch();
if(!$item){ http_response_code(404); exit('not found'); }
?><!doctype html><meta charset="utf-8"><title>작품 수정</title>
<style>body{font-family:system-ui,Segoe UI,Roboto,Malgun Gothic,sans-serif;max-width:720px;margin:6vh auto;padding:20px}label{display:block;margin:8px 0}input{padding:8px;width:100%}</style>
<h1>작품 수정 #<?=$item['id']?></h1>
<form method="post" action="/admin/comics/update.php" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?=$item['id']?>">
  <?php include __DIR__.'/_form.php'; ?>
</form>
<p><a href="/admin/comics/">목록</a></p>
