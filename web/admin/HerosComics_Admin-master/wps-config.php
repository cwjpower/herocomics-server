<?php
/*
 * 2016.07.26   softsyw
 * Desc : Main Configuration
 */

// ====================================
// 데이터베이스 설정 ✅
// ====================================
//define( 'DB_HOST', '114.31.116.199' );  // 기존 외부 DB (주석 처리)
define( 'DB_HOST', 'mariadb' );           // Docker MariaDB ✅
define( 'DB_USER', 'root' );              // DB 유저 ✅
define( 'DB_PASS', 'rootpass' );          // DB 비밀번호 ✅
define( 'DB_NAME', 'herocomics' );        // DB 이름 ✅
define( 'DB_PORT', 3306);
define( 'DB_SOCK', '' );
define( 'DB_CHARSET', 'utf8' );

// ====================================
// 사이트 URL 설정
// ====================================
$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$site_url = $protocol . $_SERVER['HTTP_HOST'];
define( 'SITE_URL', $site_url );

// define( 'SETUP_DIR', '/service' );
define( 'SETUP_DIR', '/old_20180108' );

//define( 'HOME_URL', SITE_URL . SETUP_DIR );
define( 'HOME_URL', SITE_URL);

// ====================================
// URL 상수 정의 ✅ 수정됨!
// ====================================
define( 'ADMIN_URL', HOME_URL . '/admin/HerosComics_Admin-master/admin' );
define( 'CONTENT_URL', HOME_URL . '/admin/HerosComics_Admin-master/content' );
define( 'INC_URL', HOME_URL . '/admin/HerosComics_Admin-master/includes' );
define( 'UPLOAD_URL', HOME_URL . '/admin/HerosComics_Admin-master/upload' );
//define( 'UPLOAD_URL', '/upload' );
define( 'IMG_URL', INC_URL . '/images' );
define( 'MOBILE_URL', HOME_URL . '/admin/HerosComics_Admin-master/mobile' );

// ====================================
// 경로 상수 정의
// ====================================
define( 'ABS_PATH', dirname(__FILE__) );
define( 'ADMIN_PATH', ABS_PATH . '/admin' );
define( 'CONTENT_PATH', ABS_PATH . '/content' );
define( 'INC_PATH', ABS_PATH . '/includes' );
define( 'FUNC_PATH', INC_PATH . '/functions' );
define( 'MOBILE_PATH', ABS_PATH . '/mobile' );
define( 'UPLOAD_PATH', ABS_PATH . '/upload' );
//define( 'UPLOAD_PATH',  '/upload' );

// ====================================
// 설정 파일 로드
// ====================================
require_once ABS_PATH . '/wps-settings.php';
?>