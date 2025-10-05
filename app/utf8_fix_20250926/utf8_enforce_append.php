<?php
// ==== UTF-8 ENFORCE BLOCK (safe to append) ====
if (!headers_sent()) {
  header("Content-Type: text/html; charset=UTF-8");
}
if (function_exists("mb_internal_encoding")) { @mb_internal_encoding("UTF-8"); }
@ini_set("default_charset","UTF-8");
