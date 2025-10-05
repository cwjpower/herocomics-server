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
function thumb_of(string $pagePath, int $eid): string {
  if (preg_match('#^uploads/episodes/\d+/pages/(.+)$#', $pagePath, $m)) { return 'uploads/episodes/'.$eid.'/thumbs/'.$m[1]; }
  if (preg_match('#^uploads/episodes/\d+/(?:orig|pages)/(\d{4})\.[a-z0-9]+$#i', $pagePath, $m)) { return 'uploads/episodes/'.$eid.'/thumbs/'.$m[1].'.webp'; }
  return $pagePath;
}
?>
<!doctype html><meta charset="utf-8"><title>페이지 정렬</title>
<style>body{font-family:system-ui,Segoe UI,Roboto,Malgun Gothic,sans-serif;max-width:900px;margin:6vh auto;padding:20px}table{width:100%;border-collapse:collapse}td,th{border:1px solid #ddd;padding:8px}img{height:80px}</style>
<h1>페이지 정렬 – [<?=htmlspecialchars($e['comic_title'],ENT_QUOTES,'UTF-8')?>] <?=htmlspecialchars($e['title'],ENT_QUOTES,'UTF-8')?> (#<?=$e['id']?>)</h1>
<form method="post" action="/admin/episodes/reorder_proc.php">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8')?>">
  <input type="hidden" name="id" value="<?=$e['id']?>">
  <table>
    <tr><th>썸네일</th><th>파일</th><th>현재</th><th>새 순서</th><th>삭제</th></tr>
    <?php foreach($rows as $r): $thumb = thumb_of($r['file_path'], $eid); ?>
      <tr>
        <td><?php if($thumb): ?><img src="/<?=$thumb?>"><?php endif; ?></td>
        <td><?=htmlspecialchars(basename($r['file_path']),ENT_QUOTES,'UTF-8')?></td>
        <td><?=$r['page_no']?></td>
        <td><input type="hidden" name="pid[]" value="<?=$r['id']?>"><input type="number" name="ord[]" value="<?=$r['page_no']?>" min="1" style="width:80px"></td>
        <td>
          <form method="post" action="/admin/episodes/page_delete.php" onsubmit="return confirm('이 페이지를 삭제할까요?');" style="display:inline">
            <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8')?>">
            <input type="hidden" name="id" value="<?=$r['id']?>">
            <input type="hidden" name="eid" value="<?=$e['id']?>">
            <button>삭제</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
  <p><button type="submit">순서 저장</button></p>
</form>
<p><a href="/admin/episodes/">회차 목록</a></p>