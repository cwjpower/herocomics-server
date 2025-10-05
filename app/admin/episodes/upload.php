<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login();
header('Content-Type: text/html; charset=UTF-8');
$id = (int)($_GET['id'] ?? 0);
$ep = $pdo->prepare("SELECT e.*, c.title AS comic_title FROM episode e JOIN comic c ON c.id=e.comic_id WHERE e.id=?");
$ep->execute([$id]); $e=$ep->fetch();
if(!$e){ http_response_code(404); exit('not found'); }
?><!doctype html><meta charset="utf-8"><title>이미지 업로드</title>
<style>body{font-family:system-ui,Segoe UI,Roboto,Malgun Gothic,sans-serif;max-width:760px;margin:6vh auto;padding:20px}label{display:block;margin:8px 0}</style>
<h1>이미지 업로드 – [<?=htmlspecialchars($e['comic_title'],ENT_QUOTES,'UTF-8')?>] <?=htmlspecialchars($e['title'],ENT_QUOTES,'UTF-8')?> (#<?=$e['id']?>)</h1>
<form method="post" action="/admin/episodes/upload_proc.php" enctype="multipart/form-data">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8')?>">
  <input type="hidden" name="id" value="<?=$e['id']?>">
  <label>이미지 파일들 선택 (여러 개 가능)
    <input type="file" name="files[]" accept="image/*" multiple required>
  </label>
  <p>허용: jpg/jpeg/png/webp/gif. 업로드 후 자동으로 페이지 번호가 이어서 붙습니다.</p>
  <button type="submit">업로드</button>
</form>
<p>
  <a href="/admin/episodes/reorder.php?id=<?=$e['id']?>">정렬/삭제</a> |
  <a href="/admin/episodes/">회차 목록</a>
</p>
