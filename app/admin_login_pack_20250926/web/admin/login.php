<?php
require __DIR__."/functions.php";
if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }
$err = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $u = trim($_POST["username"] ?? "");
  $p = (string)($_POST["password"] ?? "");
  $opt = ABS_PATH . "wps-options.json";
  $user = "admin"; $pass = "admin123!";
  if (is_readable($opt)) {
    $cfg = json_decode(file_get_contents($opt), true);
    if (is_array($cfg)) {
      $user = $cfg["admin_user"] ?? $user;
      $pass = $cfg["admin_pass"] ?? $pass;
    }
  }
  if (hash_equals($user, $u) && hash_equals($pass, $p)) {
    $_SESSION["user_id"] = 1;
    $_SESSION["user_name"] = $u;
    header("Location: " . ADMIN_URL . "/index.php");
    exit;
  } else {
    $err = "??? ?? ????? ???? ????.";
  }
}
if (!function_exists("page_header")) {
  function page_header($title=""){ echo "<!doctype html><html lang='ko'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>".htmlspecialchars($title,ENT_QUOTES|ENT_SUBSTITUTE,"UTF-8")."</title></head><body>"; }
}
if (!function_exists("page_footer")) { function page_footer(){ echo "</body></html>"; } }
page_header("??? ???");
echo "<style>body{font-family:system-ui,Segoe UI,Malgun Gothic,Apple SD Gothic Neo,Arial;max-width:480px;margin:40px auto;padding:20px}
h1{margin:0 0 16px}form{display:flex;flex-direction:column;gap:12px}label{display:flex;flex-direction:column;gap:6px}
input,button{padding:10px;font-size:16px}button{cursor:pointer}</style>";
echo "<h1>??? ???</h1>";
if ($err) echo "<div style='color:#b00;margin-bottom:10px;'>".htmlspecialchars($err,ENT_QUOTES|ENT_SUBSTITUTE,"UTF-8")."</div>";
echo "<form method='post' novalidate>
<label>???<input name='username' required autofocus></label>
<label>????<input type='password' name='password' required></label>
<button type='submit'>???</button>
</form>
<p style='margin-top:10px;color:#555'>?? ??: <b>admin</b> / <b>admin123!</b></p>";
page_footer();
