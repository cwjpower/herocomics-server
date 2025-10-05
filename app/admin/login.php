<?php
declare(strict_types=1);
require __DIR__.'/_bootstrap.php';
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $email = trim((string)($_POST['email'] ?? ''));
  $pass  = (string)($_POST['password'] ?? '');
  $stmt = $pdo->prepare('SELECT id,password_hash,name FROM admin_user WHERE email=?');
  $stmt->execute([$email]);
  $u = $stmt->fetch();
  if ($u && password_verify($pass, $u['password_hash'])) {
    $_SESSION['admin_id'] = (int)$u['id'];
    $_SESSION['admin_name'] = (string)$u['name'];
    header('Location: /admin/'); exit;
  }
  $err = '이메일 또는 비밀번호가 올바르지 않습니다.';
}
$csrf = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
?><!doctype html><meta charset="utf-8"><title>Admin Login</title>
<style>body{font-family:system-ui,Segoe UI,Roboto,Malgun Gothic,sans-serif;max-width:420px;margin:8vh auto;padding:24px}form{display:grid;gap:12px}input,button{padding:10px;font-size:16px}.err{color:#c00}</style>
<h1>관리자 로그인</h1>
<?php if(!empty($err)): ?><p class="err"><?=$err?></p><?php endif; ?>
<form method="post">
  <input type="hidden" name="csrf" value="<?=$csrf?>">
  <input name="email" type="email" placeholder="이메일" required>
  <input name="password" type="password" placeholder="비밀번호" required>
  <button type="submit">로그인</button>
</form>
