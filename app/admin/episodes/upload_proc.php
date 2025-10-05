<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login(); csrf_check();
require_once __DIR__.'/../config.images.php';
require_once __DIR__.'/../lib/imgproc.php';

$eid = (int)($_POST['id'] ?? 0);
if($eid<=0){ http_response_code(400); exit('bad id'); }

$allow = ['jpg','jpeg','png','webp','gif'];
$baseDir = dirname(__DIR__,2)."/uploads/episodes/".$eid;
$origDir = $baseDir."/orig";
if(!is_dir($origDir)){ mkdir($origDir,0777,true); }

$st = $pdo->prepare("SELECT COALESCE(MAX(page_no),0) AS m FROM page WHERE episode_id=?");
$st->execute([$eid]); $maxNo = (int)$st->fetchColumn();
$ins = $pdo->prepare("INSERT INTO page(episode_id,page_no,file_path,width,height) VALUES(?,?,?,?,?)");

$files = $_FILES['files'] ?? null;
if(!$files || !is_array($files['name'])){ http_response_code(400); exit('no files'); }

$cnt = count($files['name']);
for($i=0;$i<$cnt;$i++){
  if((int)$files['error'][$i]!==UPLOAD_ERR_OK){ continue; }
  $tmp = $files['tmp_name'][$i];
  $ext = strtolower((string)pathinfo((string)$files['name'][$i], PATHINFO_EXTENSION));
  if(!in_array($ext,$allow,true)){ continue; }

  $maxNo++;
  $stem  = str_pad((string)$maxNo,4,'0',STR_PAD_LEFT);
  $orig  = $origDir.'/'.$stem.'.'.$ext;
  if(!move_uploaded_file($tmp, $orig)){ $maxNo--; continue; }

  try {
    $out = img_process_all($orig, $baseDir, IMG_MAX_WIDTH, IMG_THUMB_WIDTH, IMG_OUTPUT_FORMAT, IMG_QUALITY);
    $rel = 'uploads/episodes/'.$eid.'/'.str_replace($baseDir.'/', '', $out['page_path']);
    $ins->execute([$eid,$maxNo,$rel,$out['w'],$out['h']]);
  } catch (Throwable $e) {
    $rel = 'uploads/episodes/'.$eid.'/orig/'.$stem.'.'.$ext;
    $sz = @getimagesize($orig); $w = $sz ? (int)$sz[0] : null; $h = $sz ? (int)$sz[1] : null;
    $ins->execute([$eid,$maxNo,$rel,$w,$h]);
  }
}
flash('msg','업로드 완료');
header('Location: /admin/episodes/reorder.php?id='.$eid); exit;