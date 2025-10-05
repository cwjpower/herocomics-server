<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login();
header('Content-Type: text/html; charset=UTF-8');
$kw = trim((string)($_GET['q'] ?? ''));
if ($kw !== '') { $stmt = $pdo->prepare('SELECT * FROM comic WHERE title LIKE ? ORDER BY id DESC LIMIT 50'); $stmt->execute(['%'.$kw.'%']); }
else { $stmt = $pdo->query('SELECT * FROM comic ORDER BY id DESC LIMIT 50'); }
$list = $stmt->fetchAll(); $msg = htmlspecialchars((string) (flash('msg') ?? ''), ENT_QUOTES, 'UTF-8');
?><!doctype html><meta charset="utf-8"><title>Comics</title>
<style>body{font-family:system-ui,Segoe UI,Roboto,Malgun Gothic,sans-serif;max-width:900px;margin:6vh auto;padding:20px}table{width:100%;border-collapse:collapse}td,th{border:1px solid #ddd;padding:8px}.top{display:flex;gap:12px;align-items:center;margin-bottom:12px}.msg{color:#0a0}</style>
<h1>작품 목록</h1>
<div class="top">
  <form method="get"><input name="q" value="<?=htmlspecialchars($kw,ENT_QUOTES,'UTF-8')?>" placeholder="제목 검색"><button>검색</button></form>
  <a href="/admin/comics/new.php">+ 새 작품</a>
  <a href="/admin/">대시보드</a>
</div>
<?php if($msg): ?><p class="msg"><?=$msg?></p><?php endif; ?>
<table>
  <tr><th>ID</th><th>제목</th><th>작가</th><th>상태</th><th>표지</th><th>수정</th><th>삭제</th></tr>
  <?php foreach($list as $r): ?>
    <tr>
      <td><?=$r['id']?></td>
      <td><?=htmlspecialchars($r['title'],ENT_QUOTES,'UTF-8')?></td>
      <td><?=htmlspecialchars($r['author'],ENT_QUOTES,'UTF-8')?></td>
      <td><?=$r['status'] ? '공개' : '비공개'?></td>
      <td><?php if($r['cover_path']): ?><img src="/<?=$r['cover_path']?>" style="height:50px"><?php endif; ?></td>
      <td><a href="/admin/comics/edit.php?id=<?=$r['id']?>">수정</a></td>
      <td>
        <form method="post" action="/admin/comics/delete.php" onsubmit="return confirm('삭제할까요?');" style="display:inline">
          <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8')?>">
          <input type="hidden" name="id" value="<?=$r['id']?>">
          <button>삭제</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
