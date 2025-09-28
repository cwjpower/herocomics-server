<?php
// /web/admin/_layout.php
function admin_header($title="히어로 코믹스 어드민"){
  $t = htmlspecialchars($title, ENT_QUOTES|ENT_SUBSTITUTE, "UTF-8");
  echo "<!doctype html><html lang='ko'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'>";
  echo "<title>{$t}</title><link rel='stylesheet' href='".ADMIN_URL."/admin.css'></head><body>";
  echo "<header class='topbar'><strong>히어로 코믹스 어드민</strong><nav>";
  echo "<a href='".ADMIN_URL."/index.php'>대시보드</a>";
  echo "<a href='".ADMIN_URL."/posts.php'>작품</a>";
  echo "<a href='".ADMIN_URL."/publishers.php'>출판사</a>";
  echo "<a href='".ADMIN_URL."/banners.php'>배너</a>";
  echo "<a href='".ADMIN_URL."/users.php'>사용자</a>";
  echo "<a class='right' href='".ADMIN_URL."/logout.php'>로그아웃</a>";
  echo "</nav></header><main class='container'>";
}
function admin_footer(){ echo "</main></body></html>"; }
