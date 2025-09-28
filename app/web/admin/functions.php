<?php
$__THIS=__FILE__;
$__ADM=dirname($__THIS);
$__WEB=dirname($__ADM);
$__APP=dirname($__WEB);

set_include_path(implode(PATH_SEPARATOR, array_unique(array_filter([
  get_include_path(),
  $__APP,
  $__WEB,
  $__WEB."/functions",
  $__APP."/includes",
  $__APP."/includes/functions",
]))));

$__vars=$__APP."/wps-vars.php";                          if (is_readable($__vars)) require_once $__vars;
$__wps =$__APP."/includes/functions/functions-wps.php";  if (is_readable($__wps))  require_once $__wps;
$__cms =$__APP."/includes/functions/functions-cms-stubs.php"; if (is_readable($__cms)) require_once $__cms;

/* UTF-8 enforce */
if (!headers_sent()) { header("Content-Type: text/html; charset=UTF-8"); }
@ini_set("default_charset","UTF-8");
if (function_exists("mb_internal_encoding")) @mb_internal_encoding("UTF-8");

/* Output normalizer (?? ?? ??) */
if (!defined("ADMIN_UTF8_OB")) {
  define("ADMIN_UTF8_OB", true);
  ob_start(function($buf){
    if ($buf==="" || (function_exists("mb_check_encoding") && mb_check_encoding($buf,"UTF-8"))) return $buf;
    foreach (["CP949","EUC-KR","EUC_KR","Windows-1252","ISO-8859-1"] as $enc) {
      $conv=@iconv($enc,"UTF-8//IGNORE",$buf);
      if ($conv!==false && $conv!=="") return $conv;
    }
    return $buf;
  });
}
