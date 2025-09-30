<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login(); csrf_check();
$id = (int)($_POST['id'] ?? 0);
if($id<=0){ http_response_code(400); exit('bad id'); }
# 폴더 삭제
$dir = dirname(__DIR__,2)."/uploads/episodes/".$id;
if(is_dir($dir)){
  $it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST
  );
  foreach($it as $f){ $f->isDir()? rmdir($f->getRealPath()) : @unlink($f->getRealPath()); }
  @rmdir($dir);
}
$pdo->prepare("DELETE FROM episode WHERE id=?")->execute([$id]);
flash('msg','삭제되었습니다.');
header("Location: /admin/episodes/"); exit;
