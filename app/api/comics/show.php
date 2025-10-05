<?php
require_once __DIR__."/../../inc/db.php";

$slug = $_GET["slug"] ?? "";
if ($slug === "") json_out(["ok"=>false, "error"=>"slug required"], 400);

$stmt = db()->prepare("SELECT id,title,slug,synopsis,author,status,created_at,updated_at FROM comics WHERE slug=?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$comic = $stmt->get_result()->fetch_assoc();
if (!$comic) json_out(["ok"=>false, "error"=>"not found"], 404);

$stmt = db()->prepare("SELECT id, number, title, published_at FROM chapters WHERE comic_id=? ORDER BY number ASC");
$stmt->bind_param("i", $comic["id"]);
$stmt->execute();
$chapters = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

json_out(["ok"=>true, "comic"=>$comic, "chapters"=>$chapters]);