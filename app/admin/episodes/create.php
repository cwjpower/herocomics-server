<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login(); csrf_check();
$comic_id = (int)($_POST['comic_id'] ?? 0);
$title    = trim((string)($_POST['title'] ?? ''));
$no       = (int)($_POST['no'] ?? 1);
$is_pub   = isset($_POST['is_published']) ? 1 : 0;
if($comic_id<=0 || $title===''){ http_response_code(400); exit('필수값 누락'); }
$pub_at = $is_pub ? date('Y-m-d H:i:s') : null;
$stmt = $pdo->prepare("INSERT INTO episode(comic_id,title,no,is_published,published_at) VALUES(?,?,?,?,?)");
$stmt->execute([$comic_id,$title,$no,$is_pub,$pub_at]);
$eid = (int)$pdo->lastInsertId();
flash('msg','회차 생성 완료. 이제 이미지를 업로드하세요.');
header("Location: /admin/episodes/upload.php?id=".$eid); exit;
