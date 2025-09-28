<?php
// /web/admin/_auth.php  (auto-init + bcrypt + CSRF)
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_set_cookie_params(["lifetime"=>0,"path"=>"/","domain"=>"","secure"=>false,"httponly"=>true,"samesite"=>"Lax"]);
  @session_start();
}

function csrf_token(){ if (empty($_SESSION["csrf"])) $_SESSION["csrf"]=bin2hex(random_bytes(32)); return $_SESSION["csrf"]; }
function csrf_field(){ echo '<input type="hidden" name="csrf" value="'.htmlspecialchars(csrf_token(),ENT_QUOTES).'">'; }
function verify_csrf(){ $ok=isset($_POST["csrf"]) && hash_equals($_SESSION["csrf"]??"", (string)($_POST["csrf"]??"")); if(!$ok){ http_response_code(400); exit("잘못된 요청(CSRF)"); } }

function user_store_path(){ return ABS_PATH.'wps-users.json'; }
function option_path(){ return ABS_PATH.'wps-options.json'; }

// 사용자 저장소 자동 초기화
function ensure_user_store(){
  $ujson = user_store_path();
  $ojson = option_path();
  $defUser = 'admin'; $defPass = 'admin123!';
  if (is_readable($ojson)) {
    $cfg = json_decode(@file_get_contents($ojson), true);
    if (is_array($cfg)) {
      $defUser = $cfg['admin_user'] ?? $defUser;
      $defPass = $cfg['admin_pass'] ?? $defPass;
    }
  }
  $needInit = true;
  if (is_readable($ujson)) {
    $list = json_decode(@file_get_contents($ujson), true);
    if (is_array($list) && count($list)>0 && isset($list[0]['username'])) $needInit = false;
  }
  if ($needInit) {
    $hash = password_hash((string)$defPass, PASSWORD_DEFAULT);
    $admin = ["id"=>1,"username"=>$defUser,"role"=>"admin","password_hash"=>$hash];
    @file_put_contents($ujson, json_encode([$admin], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
  }
}

function load_users(){ ensure_user_store(); $p=user_store_path(); $d=@file_get_contents($p); $j=json_decode((string)$d,true); return is_array($j)?$j:[]; }
function find_user($username){
  foreach(load_users() as $u){
    if(isset($u["username"]) && strtolower($u["username"])===strtolower((string)$username)) return $u;
  }
  return null;
}
function login_user(array $u){ session_regenerate_id(true); $_SESSION["user_id"]=$u["id"]; $_SESSION["user_name"]=$u["username"]; $_SESSION["role"]=$u["role"]??"admin"; }
function require_login(){ if (empty($_SESSION["user_id"])) { header("Location: ".ADMIN_URL."/login.php"); exit; } }
function logout_user(){ $_SESSION=[]; if (ini_get("session.use_cookies")) { $p=session_get_cookie_params(); setcookie(session_name(),"",time()-42000,$p["path"],$p["domain"],$p["secure"],$p["httponly"]); } @session_destroy(); }

// ★ include 되는 즉시 1회 초기화
if (!defined('ADMIN_AUTH_AUTO_INIT')) { define('ADMIN_AUTH_AUTO_INIT', true); ensure_user_store(); }
