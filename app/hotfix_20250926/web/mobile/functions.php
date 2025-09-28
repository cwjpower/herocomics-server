<?php
$__THIS = __FILE__;
$__MOB  = dirname($__THIS);
$__WEB  = dirname($__MOB);
$__APP  = dirname($__WEB);

set_include_path(implode(PATH_SEPARATOR, array_unique(array_filter([
  get_include_path(),
  $__APP,
  $__WEB,
  $__WEB."/functions",
  $__APP."/includes",
  $__APP."/includes/functions",
]))));

$__page = $__APP . "/includes/functions/functions-page.php";
if (is_readable($__page)) require_once $__page;

$__vars = $__APP . "/wps-vars.php";
if (is_readable($__vars)) require_once $__vars;

$__wps = $__APP . "/includes/functions/functions-wps.php";
if (is_readable($__wps)) require_once $__wps;

$__cms = $__APP . "/includes/functions/functions-cms-stubs.php";
if (is_readable($__cms)) require_once $__cms;
