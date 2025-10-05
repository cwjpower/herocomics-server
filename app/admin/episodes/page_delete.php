<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login(); csrf_check();
$pid = (int)($_POST['id'] ?? 0);
$eid = (int)($_POST['eid'] ?? 0);
if($pid<=0 || $eid<=0){ http_response_code(400); exit('bad'); }
$st = $pdo->prepare("SELECT file_path FROM page WHERE id=? AND episode_id=?");
$st->execute([$pid,$eid]); $r=$st->fetch();
if($r){
  @unlink(dirname(__DIR__,2)."/".$r['file_path']);
  $pdo->prepare("DELETE FROM page WHERE id=? AND episode_id=?")->execute([$pid,$eid]);
}
flash('msg','페이지 삭제');
header('Location: /admin/episodes/reorder.php?id='.$eid); exit;
