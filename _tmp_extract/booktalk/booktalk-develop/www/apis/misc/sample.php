<?php
/*
 * 2016.10.15		softsyw
 * Desc : Sample API 
 */
date_default_timezone_set('Asia/Seoul');

require_once '../../wps-config.php';

$code = 0;
$msg = '';

$log_get = date('Y.m.d H:i:s') . ' GET : ' . print_r($_GET, true);
$log_post = date('Y.m.d H:i:s') . ' POST : ' . print_r($_POST, true);
$log_request = date('Y.m.d H:i:s') . ' REQUEST : ' . print_r($_REQUEST, true);
$log_server = date('Y.m.d H:i:s') . ' SERVER : ' . print_r($_SERVER, true);

$log = $log_get . "\n" . $log_post . "\n" . $log_request . "\n" . $log_server;

lps_error_log( $log, UPLOAD_PATH . '/tmp/api_log.txt', 1);


if ( empty($_POST['user_id']) ) {
	$code = 9901;
	$msg = 'user_id가 필요합니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_POST['user_login']) ) {
	$code = 9902;
	$msg = 'user_login이 필요합니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_POST['user_name']) ) {
	$code = 9903;
	$msg = 'user_name이 필요합니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}


// Logging
function lps_error_log ($message, $filename = null, $message_type = null) {
// 	file_put_contents($filename, $message, FILE_APPEND | LOCK_EX);
}


$json = compact('code', 'msg');
echo json_encode( $json );

?>