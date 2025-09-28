<?php
require_once __DIR__."/../../inc/db.php";
$limit = isset($_GET["limit"]) ? max(1, min(100, (int)$_GET["limit"])) : 50;

$sql = "SELECT id, title, slug, synopsis, author, status, created_at
        FROM comics
        ORDER BY id DESC
        LIMIT ?";
$stmt = db()->prepare($sql);
$stmt->bind_param("i", $limit);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

json_out(["ok"=>true, "items"=>$items]);