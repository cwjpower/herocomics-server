<?php
require __DIR__."/functions.php";
require __DIR__."/_auth.php";
require __DIR__."/_layout.php";
require __DIR__."/_data.php";
require_login();

$rows = data_load("posts");

admin_header("작품 관리");
echo "<div class='card'><a class='btn' href='".ADMIN_URL."/posts_edit.php'>+ 새 작품</a></div>";

if (!$rows){
  echo "<div class='card'>아직 등록된 작품이 없습니다.</div>";
} else {
  echo "<div class='card'><table style=\"width:100%;border-collapse:collapse\">";
  echo "<tr><th style='text-align:left;padding:8px'>ID</th><th style='text-align:left;padding:8px'>제목</th><th style='text-align:left;padding:8px'>작가</th><th style='text-align:left;padding:8px'>상태</th><th></th></tr>";
  foreach ($rows as $r){
    $id=(int)($r["id"]??0); $t=htmlspecialchars($r["title"]??"",ENT_QUOTES|ENT_SUBSTITUTE,"UTF-8");
    $a=htmlspecialchars($r["author"]??"",ENT_QUOTES|ENT_SUBSTITUTE,"UTF-8");
    $s=htmlspecialchars($r["status"]??"draft",ENT_QUOTES|ENT_SUBSTITUTE,"UTF-8");
    echo "<tr>";
    echo "<td style='padding:8px;border-top:1px solid #eee'>{$id}</td>";
    echo "<td style='padding:8px;border-top:1px solid #eee'>{$t}</td>";
    echo "<td style='padding:8px;border-top:1px solid #eee'>{$a}</td>";
    echo "<td style='padding:8px;border-top:1px solid #eee'>{$s}</td>";
    echo "<td style='padding:8px;border-top:1px solid #eee;text-align:right'>";
    echo "<a class='btn' style='background:#64748b' href='".ADMIN_URL."/posts_edit.php?id={$id}'>수정</a> ";
    echo "<form method='post' action='".ADMIN_URL."/posts_delete.php' style='display:inline' onsubmit='return confirm(\"삭제할까요?\")'>";
    echo "<input type='hidden' name='id' value='{$id}'>"; csrf_field();
    echo "<button class='btn' type='submit' style='background:#ef4444'>삭제</button></form>";
    echo "</td></tr>";
  }
  echo "</table></div>";
}
admin_footer();
