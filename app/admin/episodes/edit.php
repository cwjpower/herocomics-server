<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login();
header('Content-Type: text/html; charset=UTF-8');
$id = (int)($_GET['id'] ?? 0);
$ep = $pdo->prepare("SELECT * FROM episode WHERE id=?"); $ep->execute([$id]); $e=$ep->fetch();
if(!$e){ http_response_code(404); exit('not found'); }
$comics = $pdo->query("SELECT id,title FROM comic ORDER BY title ASC")->fetchAll();
?><!doctype html><meta charset="utf-8"><title>회차 수정</title>
<style>body{font-family:system-ui,Segoe UI,Roboto,Malgun Gothic,sans-serif;max-width:720px;margin:6vh auto;padding:20px}label{display:block;margin:8px 0}</style>
<h1>회차 수정 #<?=$e['id']?></h1>
<form method="post" action="/admin/episodes/update.php">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8')?>">
  <input type="hidden" name="id" value="<?=$e['id']?>">
  <label>작품
    <select name="comic_id" required>
      <?php foreach($comics as $c): ?>
        <option value="<?=$c['id']?>" <?=$c['id']==$e['comic_id']?'selected':''?>><?=htmlspecialchars($c['title'],ENT_QUOTES,'UTF-8')?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>회차 제목 <input name="title" required maxlength="200" value="<?=htmlspecialchars($e['title'],ENT_QUOTES,'UTF-8')?>"></label>
  <label>회차 번호 <input name="no" type="number" value="<?=$e['no']?>" required></label>
  <label><input type="checkbox" name="is_published" value="1" <?=$e['is_published']?'checked':''?>> 공개</label>
  <button type="submit">저장</button>
</form>
<p><a href="/admin/episodes/">목록</a></p>
