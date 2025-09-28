<?php
require __DIR__."/functions.php";
require __DIR__."/_auth.php";
require_login();
require __DIR__."/_layout.php";

admin_header("히어로 코믹스 어드민");
$name = htmlspecialchars($_SESSION["user_name"] ?? "관리자", ENT_QUOTES|ENT_SUBSTITUTE,"UTF-8");
echo "<h2>어서오세요, {$name}</h2>";
echo "<div class='grid'>";
echo "<div class='card'><div class='muted'>작품</div><div><b>0</b></div></div>";
echo "<div class='card'><div class='muted'>출판사</div><div><b>0</b></div></div>";
echo "<div class='card'><div class='muted'>배너</div><div><b>0</b></div></div>";
echo "<div class='card'><div class='muted'>사용자</div><div><b>1</b></div></div>";
echo "</div>";
admin_footer();
