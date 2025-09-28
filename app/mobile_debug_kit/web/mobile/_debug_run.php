<?php
@ini_set("display_errors","1");
error_reporting(E_ALL);
if (!ob_get_level()) { ob_start(); }
register_shutdown_function(function(){
    $out = ob_get_contents(); @file_put_contents("/tmp/mobile_output.html", (string)$out);
    $err = error_get_last();  @file_put_contents("/tmp/mobile_last_error.txt", var_export($err, true));
});
if (function_exists("header_register_callback")) {
    header_register_callback(function(){ @file_put_contents("/tmp/mobile_headers.txt", print_r(headers_list(), true)); });
}
require __DIR__ . "/index.php";
