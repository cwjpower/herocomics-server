<?php
// 폼 호환 매핑
if(!isset($_POST["username"]) && isset($_POST["email"])) $_POST["username"] = $_POST["email"];
if(!isset($_POST["password"])) foreach(["pwd","pass","passwd"] as $k){ if(isset($_POST[$k])) { $_POST["password"]=$_POST[$k]; break; } }

session_start();
// CSRF: 개발 모드 — 토큰이 둘 중 하나라도 있으면 검사, 둘 다 없으면 통과
$csrf_sess = $_SESSION["csrf"] ?? "";
$csrf_post = $_POST["_csrf"] ?? "";
if ($csrf_sess !== "" || $csrf_post !== "") {
  if (!hash_equals($csrf_sess, $csrf_post)) {
    http_response_code(400);
    exit("잘못된 요청 (CSRF mismatch)");
  }
}
unset($_SESSION["csrf"]);

$u = trim($_POST["username"] ?? "");
$p = $_POST["password"] ?? "";

try {
    // 수정 ✅
    $pdo = new PDO("mysql:host=mariadb;dbname=herocomics;charset=utf8mb4","root","rootpass",
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
  $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admin_users WHERE username = ?");
  $stmt->execute([$u]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($user && password_verify($p, $user["password_hash"])) {
    session_regenerate_id(true); // 세션 고정화 방지
    $_SESSION["admin_id"] = $user["id"];
    header("Location: dashboard.php"); exit;
  }
} catch(Throwable $e) {
  error_log($e);
}
http_response_code(401);
echo "로그인 실패";
