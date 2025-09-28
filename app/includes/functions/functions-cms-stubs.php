<?php
// ?? ??? ? ??? "?? ???" ??
if (!function_exists("wps_get_current_user_id")) {
  function wps_get_current_user_id(){ return isset($_SESSION["user_id"])?(int)$_SESSION["user_id"]:(int)($_COOKIE["WPS_UID"]??0); }
}
if (!function_exists("wps_is_logged_in")) { function wps_is_logged_in(){ return wps_get_current_user_id() > 0; } }
if (!function_exists("wps_get_current_user")) {
  function wps_get_current_user(){ $id=wps_get_current_user_id(); return $id?["id"=>$id,"name"=>($_SESSION["user_name"]??($_COOKIE["WPS_UNAME"]??"???"))]:null; }
}
if (!function_exists("wps_current_user_can")) { function wps_current_user_can($cap){ return wps_is_logged_in(); } }

// ?? ?? ??
foreach (["lps_get_posts_by_type","lps_get_publishers","lps_get_categories","lps_get_banners","lps_get_books","lps_get_series","lps_get_authors","lps_get_book","lps_get_main_curations"] as $fn) {
  if (!function_exists($fn)) { eval("function $fn(...\$args){ return []; }"); }
}
// ??? ?? (??)? ??
foreach (["aps_is_admin","aps_get_admins","aps_get_permissions","aps_authenticate","aps_get_menu","aps_get_stats"] as $fn) {
  if (!function_exists($fn)) { eval("function $fn(...\$args){ return []; }"); }
}
if(!function_exists('lps_get_total_sale_book')){function lps_get_total_sale_book(...$args){return [];}}
if(!function_exists('lps_get_today_sale_book')){function lps_get_today_sale_book(...$args){return [];}}
if(!function_exists('lps_get_share_count')){function lps_get_share_count(...$args){return [];}}
if(!function_exists('lps_get_today_join_user')){function lps_get_today_join_user(...$args){return [];}}
if(!function_exists('lps_get_best_genre_book')){function lps_get_best_genre_book(...$args){return [];}}
if(!function_exists('lps_get_best_selling_book')){function lps_get_best_selling_book(...$args){return [];}}
if(!function_exists('lps_get_best_selling_set')){function lps_get_best_selling_set(...$args){return [];}}
if(!function_exists('lps_get_best_ranking_book')){function lps_get_best_ranking_book(...$args){return [];}}
if(!function_exists('lps_get_total_accepted_book')){function lps_get_total_accepted_book(...$args){return [];}}
if(!function_exists('lps_get_total_waiting_new_book')){function lps_get_total_waiting_new_book(...$args){return [];}}
if(!function_exists('lps_get_total_waiting_update_book')){function lps_get_total_waiting_update_book(...$args){return [];}}
if(!function_exists('lps_get_total_waiting_qna')){function lps_get_total_waiting_qna(...$args){return [];}}
if(!function_exists('lps_get_user_avatar')){function lps_get_user_avatar(...$args){return [];}}
