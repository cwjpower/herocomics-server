<?php
require __DIR__."/functions.php";
require __DIR__."/_auth.php";
require __DIR__."/_data.php";
require_login();
if ($_SERVER["REQUEST_METHOD"]==="POST") {
  verify_csrf();
  $id = (int)($_POST["id"]??0);
  $rows = data_load("posts");
  $out = [];
  foreach ($rows as $r){ if ((int)($r["id"]??0)!==$id) $out[]=$r; }
  data_save("posts",$out);
}
header("Location: ".ADMIN_URL."/posts.php"); exit;
