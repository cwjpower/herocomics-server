<?php
declare(strict_types=1);
if (php_sapi_name() !== "cli") { http_response_code(403); exit("CLI only"); }
require __DIR__."/../app/admin/_bootstrap.php";
require_once __DIR__."/../app/admin/config.images.php";
require_once __DIR__."/../app/admin/lib/imgproc.php";

$st = $pdo->query("SELECT id, episode_id, page_no, file_path FROM page ORDER BY episode_id, page_no");
$rows = $st->fetchAll();
$done=0; $fail=0;
foreach($rows as $r){
  $eid = (int)$r["episode_id"];
  $base = "/var/www/html/uploads/episodes/".$eid;
  if(!is_dir($base)) @mkdir($base,0777,true);
  $curRel = $r["file_path"];
  $absCur = "/var/www/html/".$curRel;
  if(!file_exists($absCur)){ echo "skip missing: {$curRel}\n"; $fail++; continue; }

  $origDir = $base."/orig";
  if(!is_dir($origDir)) @mkdir($origDir,0777,true);
  $stem = str_pad((string)$r["page_no"],4,"0",STR_PAD_LEFT);
  $ext  = strtolower(pathinfo($absCur, PATHINFO_EXTENSION) ?: "jpg");
  $destOrig = $origDir."/".$stem.".".$ext;
  if($absCur !== $destOrig) @rename($absCur,$destOrig);

  try{
    $out = img_process_all($destOrig,$base,IMG_MAX_WIDTH,IMG_THUMB_WIDTH,IMG_OUTPUT_FORMAT,IMG_QUALITY);
    $newRel = "uploads/episodes/".$eid."/".str_replace($base."/", "", $out["page_path"]);
    $pdo->prepare("UPDATE page SET file_path=? WHERE id=?")->execute([$newRel,(int)$r["id"]]);
    $done++;
  }catch(Throwable $e){ echo "fail id={$r["id"]}: ".$e->getMessage()."\n"; $fail++; }
}
echo "Reprocess done: success={$done}, fail={$fail}\n";