<?php
if (!function_exists('wps_get_current_user_id')) {
  function wps_get_current_user_id() {
    // ??? ?? ????? ?? ??, ??? ?? fallback
    if (isset($_SESSION) && isset($_SESSION['user_id'])) return (int)$_SESSION['user_id'];
    if (isset($_COOKIE['WPS_UID'])) return (int)$_COOKIE['WPS_UID'];
    return 0; // ????
  }
}
if (!function_exists('wps_is_logged_in')) {
  function wps_is_logged_in() { return wps_get_current_user_id() > 0; }
}
if (!function_exists('wps_get_current_user')) {
  function wps_get_current_user() {
    $id = wps_get_current_user_id();
    if ($id > 0) {
      $name = $_SESSION['user_name'] ?? ($_COOKIE['WPS_UNAME'] ?? '???');
      return ['id'=>$id, 'name'=>$name];
    }
    return null;
  }
}
