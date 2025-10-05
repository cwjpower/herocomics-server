<?php
if (!function_exists("wps_get_option")) {
  function wps_get_option($key, $default = null) {
    $key = (string)$key; static $store = [];
    $ek1 = "WPS_" . strtoupper(str_replace([".","-"], "_", $key));
    $ek2 = strtoupper(str_replace([".","-"], "_", $key));
    foreach ([$ek1,$ek2] as $ek) { $v = getenv($ek); if ($v !== false) return $v; }
    if (array_key_exists($key,$store)) return $store[$key];
    $json = "/var/www/html/wps-options.json";
    if (is_readable($json)) {
      $data = json_decode(file_get_contents($json), true);
      if (is_array($data) && array_key_exists($key,$data)) return $data[$key];
    }
    return $default;
  }
}
if (!function_exists("wps_set_option")) { function wps_set_option($k,$v){ static $s=[]; $s[(string)$k]=$v; return true; } }
if (!function_exists("wps_is_enabled")) {
  function wps_is_enabled($v,$def=false){
    if ($v===null) return (bool)$def;
    $x=is_string($v)?strtolower(trim($v)):$v;
    if (is_bool($x)) return $x;
    return in_array($x,["1","true","yes","y","on"],true);
  }
}
