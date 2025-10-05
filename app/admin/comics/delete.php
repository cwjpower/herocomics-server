<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login(); csrf_check();
$id = (int)($_POST['id'] ?? 0);
$stmt = $pdo->prepare('SELECT cover_path FROM comic WHERE id=?');
$stmt->execute([$id]);
$cur = $stmt->fetch();
if($cur && $cur['cover_path']){ @unlink(dirname(__DIR__,2).'/'+$cur['cover_path']); }
$pdo->prepare('DELETE FROM comic WHERE id=?')->execute([$id]);
flash('msg','삭제 완료');
header('Location: /admin/comics/'); exit;
