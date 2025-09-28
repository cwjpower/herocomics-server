<?php
require __DIR__."/functions.php";
require_once __DIR__."/_auth.php";

$err="";
if ($_SERVER["REQUEST_METHOD"]==="POST") {
  verify_csrf();
  $u = trim($_POST["username"]??"");
  $p = (string)($_POST["password"]??"");
  $user = find_user($u);
  if ($user && isset($user["password_hash"]) && password_verify($p, $user["password_hash"])) {
    login_user($user);
    header("Location: ".ADMIN_URL."/index.php"); exit;
  } else {
    $err="아이디 또는 비밀번호가 올바르지 않습니다.";
  }
}

echo "<!doctype html><html lang='ko'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'>";
echo "<title>히어로 코믹스 어드민 로그인</title><link rel='stylesheet' href='".ADMIN_URL."/admin.css'></head><body>";
echo "<main class='container' style='max-width:520px'><h1>히어로 코믹스 어드민 로그인</h1>";
if ($err) echo "<div class='card' style='border-color:#fca5a5;color:#b91c1c'>".$err."</div>";
echo "<form method='post' class='card' novalidate>";
csrf_field();
echo "<label>아이디<input name='username' required autofocus></label>";
echo "<label>비밀번호<input type='password' name='password' required></label>";
echo "<button class='btn' type='submit'>로그인</button>";
echo "</form><p class='muted'>기본 계정(테스트): <b>admin</b> / <b>admin123!</b></p></main></body></html>";
