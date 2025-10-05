<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login();
header('Content-Type: text/html; charset=UTF-8');
$comics = $pdo->query("SELECT id,title FROM comic ORDER BY title ASC")->fetchAll();
?><!doctype html><meta charset="utf-8"><title>새 회차</title>
<style>body{font-family:system-ui,Segoe UI,Roboto,Malgun Gothic,sans-serif;max-width:720px;margin:6vh auto;padding:20px}label{display:block;margin:8px 0}</style>
<h1>새 회차</h1>
<form method="post" action="/admin/episodes/create.php">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8')?>">
  <label>작품
    <select name="comic_id" required>
      <option value="">-- 선택 --</option>
      <?php foreach($comics as $c): ?>
        <option value="<?=$c['id']?>"><?=htmlspecialchars($c['title'],ENT_QUOTES,'UTF-8')?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>회차 제목 <input name="title" required maxlength="200"></label>
  <label>회차 번호 <input name="no" type="number" value="1" required></label>
  <label><input type="checkbox" name="is_published" value="1"> 공개</label>
  <button type="submit">생성</button>
</form>
<p><a href="/admin/episodes/">목록</a></p>
