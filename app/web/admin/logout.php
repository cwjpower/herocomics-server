<?php
require __DIR__."/functions.php";
require __DIR__."/_auth.php";
logout_user();
header("Location: ".ADMIN_URL."/login.php"); exit;
