<?php session_start(); ?>
<!doctype html><meta charset="utf-8">
<title>HeroComics Admin - 로그인</title>
<form method="post" action="auth.php" style="margin:40px">
  <input name="username" placeholder="아이디">
  <input name="password" type="password" placeholder="비밀번호">
  <button>로그인</button>
</form>
<p><a href="db_check.php">DB 체크</a></p>
