<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login();
header('Content-Type: text/html; charset=UTF-8');

$kw = trim((string)($_GET['q'] ?? ''));
$params = [];
$sql = "SELECT e.*, c.title AS comic_title FROM episode e JOIN comic c ON c.id=e.comic_id";
if ($kw !== '') { $sql .= " WHERE c.title LIKE ? OR e.title LIKE ?"; $params = ['%'.$kw.'%','%'.$kw.'%']; }
$sql .= " ORDER BY e.id DESC LIMIT 100";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$list = $stmt->fetchAll();
$msg = htmlspecialchars((string) (flash('msg') ?? ''), ENT_QUOTES, 'UTF-8');
?><!doctype html><meta charset="utf-8"><title>회차 관리</title>
<style>
  body{font-family:system-ui,Segoe UI,Roboto,Malgun Gothic,sans-serif;max-width:1000px;margin:6vh auto;padding:20px}
  table{width:100%;border-collapse:collapse} td,th{border:1px solid #ddd;padding:8px}
  .top{display:flex;gap:12px;align-items:center;margin-bottom:12px}
  .msg{color:#0a0}
</style>
<h1>회차 목록</h1>
<div class="top">
  <form method="get"><input name="q" value="<?=htmlspecialchars($kw,ENT_QUOTES,'UTF-8')?>" placeholder="작품/회차 검색"><button>검색</button></form>
  <a href="/admin/episodes/new.php">+ 새 회차</a>
  <a href="/admin/">대시보드</a>
</div>
<?php if($msg): ?><p class="msg"><?=$msg?></p><?php endif; ?>
<table>
  <tr><th>ID</th><th>작품</th><th>회차제목</th><th>번호</th><th>공개</th><th>페이지</th><th>업로드</th><th>정렬</th><th>수정</th><th>삭제</th></tr>
  <?php foreach($list as $r): ?>
    <tr>
      <td><?=$r['id']?></td>
      <td><?=htmlspecialchars($r['comic_title'],ENT_QUOTES,'UTF-8')?></td>
      <td><?=htmlspecialchars($r['title'],ENT_QUOTES,'UTF-8')?></td>
      <td><?=$r['no']?></td>
      <td><?=$r['is_published']?'공개':'비공개'?></td>
      <td><?php
        $cnt = (int)$pdo->query("SELECT COUNT(*) FROM page WHERE episode_id=".(int)$r['id'])->fetchColumn();
        echo $cnt;
      ?></td>
      <td><a href="/admin/episodes/upload.php?id=<?=$r['id']?>">업로드</a></td>
      <td><a href="/admin/episodes/reorder.php?id=<?=$r['id']?>">정렬</a></td>
      <td><a href="/admin/episodes/edit.php?id=<?=$r['id']?>">수정</a></td>
      <td>
        <form method="post" action="/admin/episodes/delete.php" onsubmit="return confirm('삭제할까요? 회차/페이지가 모두 삭제됩니다.');">
          <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8')?>">
          <input type="hidden" name="id" value="<?=$r['id']?>">
          <button>삭제</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
