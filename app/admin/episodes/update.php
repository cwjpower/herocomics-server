<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login(); csrf_check();
$id       = (int)($_POST['id'] ?? 0);
$comic_id = (int)($_POST['comic_id'] ?? 0);
$title    = trim((string)($_POST['title'] ?? ''));
$no       = (int)($_POST['no'] ?? 1);
$is_pub   = isset($_POST['is_published']) ? 1 : 0;
if($id<=0 || $comic_id<=0 || $title===''){ http_response_code(400); exit('필수값 누락'); }
$pub_at = $is_pub ? date('Y-m-d H:i:s') : null;
$stmt = $pdo->prepare("UPDATE episode SET comic_id=?, title=?, no=?, is_published=?, published_at=? WHERE id=?");
$stmt->execute([$comic_id,$title,$no,$is_pub,$pub_at,$id]);
flash('msg','저장되었습니다.');
header("Location: /admin/episodes/"); exit;
