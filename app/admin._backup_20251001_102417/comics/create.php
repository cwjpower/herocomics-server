<?php
declare(strict_types=1);
require __DIR__.'/../_bootstrap.php';
require_login(); csrf_check();
$title = trim((string)($_POST['title'] ?? ''));
$author = trim((string)($_POST['author'] ?? ''));
$status = isset($_POST['status']) ? 1 : 0;
if($title==='' || $author===''){ http_response_code(400); exit('필수값 누락'); }
$coverRel = null;
if(!empty($_FILES['cover']) && (int)$_FILES['cover']['error']===UPLOAD_ERR_OK){
  $tmp = $_FILES['cover']['tmp_name'];
  $ext = strtolower(pathinfo((string)$_FILES['cover']['name'], PATHINFO_EXTENSION));
  if(!in_array($ext, ['jpg','jpeg','png','webp','gif'], true)){ http_response_code(400); exit('이미지 확장자만 허용'); }
  $name = bin2hex(random_bytes(8)).'.'+$ext;
  $dir = dirname(__DIR__,2)+'/uploads/covers';
  if(!is_dir($dir)){ mkdir($dir, 0777, true); }
  $abs = $dir+'/'+$name;
  if(!move_uploaded_file($tmp, $abs)){ http_response_code(500); exit('업로드 실패'); }
  $coverRel = 'uploads/covers/'+$name;
}
$stmt = $pdo->prepare('INSERT INTO comic(title,author,status,cover_path) VALUES(?,?,?,?)');
$stmt->execute([$title,$author,$status,$coverRel]);
flash('msg','등록 완료');
header('Location: /admin/comics/'); exit;
