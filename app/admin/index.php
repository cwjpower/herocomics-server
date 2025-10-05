<?php
declare(strict_types=1);
require __DIR__.'/_bootstrap.php';
require_login();
header('Content-Type: text/html; charset=UTF-8');
?><!doctype html><meta charset="utf-8"><title>Admin</title>
<h1>Admin 대시보드</h1>
<ul>
  <li><a href="/admin/comics/">작품 관리</a></li>
  <li><a href="/admin/logout.php">로그아웃</a></li>
</ul>
