<?php
// ==== UTF-8 OUTPUT NORMALIZER (auto-prepend safe, do not duplicate) ====
if (!defined("MOBILE_UTF8_OB")) {
  define("MOBILE_UTF8_OB", true);

  // ??/?? UTF-8 ??
  if (!headers_sent()) { header("Content-Type: text/html; charset=UTF-8"); }
  @ini_set("default_charset", "UTF-8");
  if (function_exists("mb_internal_encoding")) { @mb_internal_encoding("UTF-8"); }

  // ?? ?? ??? UTF-8 ??
  ob_start(function($buf){
    if ($buf === "" || (function_exists("mb_check_encoding") && mb_check_encoding($buf, "UTF-8"))) {
      return $buf; // ?? UTF-8?? ???
    }
    $cands = ["CP949","EUC-KR","EUC_KR","Windows-1252","ISO-8859-1"];
    foreach ($cands as $enc) {
      $conv = @iconv($enc, "UTF-8//IGNORE", $buf);
      if ($conv !== false && $conv !== "" && (!function_exists("mb_check_encoding") || mb_check_encoding($conv, "UTF-8"))) {
        return $conv; // ????? ???
      }
    }
    return $buf; // ???? ?? ??
  });
}
