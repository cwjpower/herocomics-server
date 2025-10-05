<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }

$error = ''; $msg = ''; $name = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $dir = '/var/www/html/uploads/banners';
    if (!is_dir($dir)) { @mkdir($dir, 0777, true); }

    if (!isset($_FILES['file']) || ($_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $error = '업로드 실패 (파일 없음/에러)';
    } else {
        $ext  = pathinfo($_FILES['file']['name'] ?? 'bin', PATHINFO_EXTENSION) ?: 'bin';
        $name = 'test_' . date('Ymd_His') . '.' . $ext;
        $dst  = $dir . '/' . $name;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $dst)) {
            @chmod($dst, 0666);
            $msg = '업로드 성공: ' . $name;
        } else {
            $error = 'move_uploaded_file 실패';
        }
    }
}
?>
<!doctype html>
<meta charset="utf-8">
<title>배너 업로드 테스트</title>
<h1>배너 업로드 테스트</h1>

<?php if ($error): ?>
    <p style="color:red"><?=htmlspecialchars($error)?></p>
<?php endif; ?>

<?php if ($msg): ?>
    <p style="color:green"><?=htmlspecialchars($msg)?></p>
    <ul>
        <li>/uploads:       <a href="/uploads/banners/<?=htmlspecialchars($name)?>" target="_blank">/uploads/banners/<?=htmlspecialchars($name)?></a></li>
        <li>/admin/uploads: <a href="/admin/uploads/banners/<?=htmlspecialchars($name)?>" target="_blank">/admin/uploads/banners/<?=htmlspecialchars($name)?></a></li>
        <li>/web/uploads:   <a href="/web/uploads/banners/<?=htmlspecialchars($name)?>" target="_blank">/web/uploads/banners/<?=htmlspecialchars($name)?></a></li>
    </ul>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button type="submit">업로드</button>
</form>
