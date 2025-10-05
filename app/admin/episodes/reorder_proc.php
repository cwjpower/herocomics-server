<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login(); csrf_check();
$eid = (int)($_POST['id'] ?? 0);
$pids = $_POST['pid'] ?? [];
$ords = $_POST['ord'] ?? [];
if($eid<=0 || !is_array($pids) || !is_array($ords)){ http_response_code(400); exit('bad'); }

# 1) 모두 +1000 (충돌 회피)
$pdo->prepare("UPDATE page SET page_no = page_no + 1000 WHERE episode_id=?")->execute([$eid]);

# 2) 맵 적용
$upd = $pdo->prepare("UPDATE page SET page_no=? WHERE id=? AND episode_id=?");
for($i=0,$n=count($pids);$i<$n;$i++){
  $pid = (int)$pids[$i];
  $ord = max(1, (int)$ords[$i]);
  $upd->execute([$ord,$pid,$eid]);
}

flash('msg','순서 저장 완료');
header('Location: /admin/episodes/reorder.php?id='.$eid); exit;
