<?php
// ---- USER / AUTH ----
if (!function_exists('wps_get_current_user_id')) {
  function wps_get_current_user_id() {
    if (isset($_SESSION['user_id'])) return (int)$_SESSION['user_id'];
    if (isset($_COOKIE['WPS_UID']))  return (int)$_COOKIE['WPS_UID'];
    return 0;
  }
}
if (!function_exists('wps_is_logged_in')) { function wps_is_logged_in(){ return wps_get_current_user_id()>0; } }
if (!function_exists('wps_get_current_user')) {
  function wps_get_current_user(){
    $id = wps_get_current_user_id();
    if ($id>0) {
      $name = $_SESSION['user_name'] ?? ($_COOKIE['WPS_UNAME'] ?? '???');
      return ['id'=>$id,'name'=>$name];
    }
    return null;
  }
}
if (!function_exists('wps_current_user_can')) {
  function wps_current_user_can($cap){ return wps_is_logged_in(); }
}

// ---- DATA HELPERS (lps_*) ----
$__demo = defined('WPS_DEMO_DATA') && WPS_DEMO_DATA === true;

if (!function_exists('lps_get_posts_by_type')) {
  function lps_get_posts_by_type($type, $limit=10, $offset=0) {
    if (!$GLOBALS['__demo']) return [];
    return [
      ['id'=>1,'title'=>"?? ? A ($type)",'cover'=>IMG_URL.'/sample_a.jpg','url'=>WEB_URL.'/book.php?id=1'],
      ['id'=>2,'title'=>"?? ? B ($type)",'cover'=>IMG_URL.'/sample_b.jpg','url'=>WEB_URL.'/book.php?id=2'],
    ];
  }
}
if (!function_exists('lps_get_publishers')) {
  function lps_get_publishers($limit=10,$offset=0) {
    if (!$GLOBALS['__demo']) return [];
    return [['id'=>101,'name'=>'Demo Publisher','logo'=>IMG_URL.'/publisher_demo.png']];
  }
}
if (!function_exists('lps_get_categories')) {
  function lps_get_categories($limit=100,$offset=0) {
    if (!$GLOBALS['__demo']) return [];
    return [['id'=>11,'name'=>'??'],['id'=>12,'name'=>'???'],['id'=>13,'name'=>'???']];
  }
}
if (!function_exists('lps_get_banners')) {
  function lps_get_banners($limit=5) {
    if (!$GLOBALS['__demo']) return [];
    return [['id'=>201,'title'=>'??-??','image'=>IMG_URL.'/banner_demo.jpg','url'=>WEB_URL.'/promo.php?id=201']];
  }
}
if (!function_exists('lps_get_books')) {
  function lps_get_books(array $args=[]) {
    if (!$GLOBALS['__demo']) return [];
    return [
      ['id'=>1001,'title'=>'??? 1','cover'=>IMG_URL.'/book1.jpg'],
      ['id'=>1002,'title'=>'??? 2','cover'=>IMG_URL.'/book2.jpg'],
    ];
  }
}
if (!function_exists('lps_get_books_by_publisher')) {
  function lps_get_books_by_publisher($publisher_id,$limit=10,$offset=0){
    if (!$GLOBALS['__demo']) return [];
    return [['id'=>1101,'title'=>"???? $publisher_id - ??",'cover'=>IMG_URL.'/pbook1.jpg']];
  }
}
if (!function_exists('lps_get_series'))  { function lps_get_series($limit=10,$offset=0){ return $GLOBALS['__demo']?[['id'=>2101,'title'=>'???-??']]:[]; } }
if (!function_exists('lps_get_authors')) { function lps_get_authors($limit=10,$offset=0){ return $GLOBALS['__demo']?[['id'=>3101,'name'=>'??-??']]:[]; } }
if (!function_exists('lps_get_book'))    { function lps_get_book($id){ return $GLOBALS['__demo']?['id'=>$id,'title'=>"??? $id",'cover'=>IMG_URL.'/book1.jpg']:null; } }
if (!function_exists('lps_get_main_curations')) {
  function lps_get_main_curations(array $args=[]){
    if (!$GLOBALS['__demo']) return [];
    return [
      ['key'=>'featured','title'=>'??','items'=>[
        ['id'=>3001,'title'=>'??-??1','cover'=>IMG_URL.'/cur1.jpg','url'=>WEB_URL.'/book.php?id=3001'],
        ['id'=>3002,'title'=>'??-??2','cover'=>IMG_URL.'/cur2.jpg','url'=>WEB_URL.'/book.php?id=3002'],
      ]],
      ['key'=>'new','title'=>'??','items'=>[
        ['id'=>3101,'title'=>'??-??1','cover'=>IMG_URL.'/new1.jpg','url'=>WEB_URL.'/book.php?id=3101'],
      ]],
      ['key'=>'hot','title'=>'??','items'=>[
        ['id'=>3201,'title'=>'??-??1','cover'=>IMG_URL.'/hot1.jpg','url'=>WEB_URL.'/book.php?id=3201'],
      ]],
    ];
  }
}
