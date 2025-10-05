<?php
session_start();
if (!isset($_SESSION["admin_id"])) { header("Location: login.php"); exit; }
?><!doctype html><meta charset="utf-8"><h1>관리자 대시보드</h1>
<p>로그인 성공! <a href="logout.php">로그아웃</a></p>
