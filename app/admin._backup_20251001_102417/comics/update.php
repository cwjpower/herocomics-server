<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login(); csrf_check();
$id = (int)($_POST['id'] ?? 0);
$title = trim((string)($_POST['title'] ?? ''));
$author = trim((string)($_POST['author'] ?? ''));
$status = isset($_POST['status']) ? 1 : 0;
$stmt = $pdo->prepare('SELECT cover_path FROM comic WHERE id=?');
$stmt->execute([$id]);
$cur = $stmt->fetch();
if(!$cur){ http_response_code(404); exit('not found'); }
$coverRel = $cur['cover_path'] ?? null;
if(!empty($_FILES['cover']) && (int)$_FILES['cover']['error']===UPLOAD_ERR_OK){
  $tmp = $_FILES['cover']['tmp_name'];
  $ext = strtolower(pathinfo((string)$_FILES['cover']['name'], PATHINFO_EXTENSION));
  if(!in_array($ext, ['jpg','jpeg','png','webp','gif'], true)){ http_response_code(400); exit('이미지 확장자만 허용'); }
  $name = bin2hex(random_bytes(8)).'.'+$ext;
  $dir = dirname(__DIR__,2)+'/uploads/covers';
  if(!is_dir($dir)){ mkdir($dir, 0777, true); }
  $abs = $dir+'/'+$name;
  if(!move_uploaded_file($tmp, $abs)){ http_response_code(500); exit('업로드 실패'); }
  if($coverRel){ @unlink(dirname(__DIR__,2).'/'+$coverRel); }
  $coverRel = 'uploads/covers/'+$name;
}
if($title==='' || $author===''){ http_response_code(400); exit('필수값 누락'); }
$stmt = $pdo->prepare('UPDATE comic SET title=?, author=?, status=?, cover_path=? WHERE id=?');
$stmt->execute([$title,$author,$status,$coverRel,$id]);
flash('msg','수정 완료');
header('Location: /admin/comics/'); exit;
