<?php
require_once __DIR__."/../../inc/db.php";
$chapter_id = isset($_GET["chapter_id"]) ? (int)$_GET["chapter_id"] : 0;
if ($chapter_id <= 0) json_out(["ok"=>false, "error"=>"chapter_id required"], 400);

$stmt = db()->prepare("SELECT id, page_no, image_path FROM pages WHERE chapter_id=? ORDER BY page_no ASC");
$stmt->bind_param("i", $chapter_id);
$stmt->execute();
$pages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

json_out(["ok"=>true, "pages"=>$pages]);