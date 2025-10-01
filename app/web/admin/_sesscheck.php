<?php
header("Content-Type: text/plain; charset=utf-8");
session_start();
$_SESSION["ping"]=($_SESSION["ping"]??0)+1;
echo "sid=".session_id()."\n";
echo "ping=".$_SESSION["ping"]."\n";

