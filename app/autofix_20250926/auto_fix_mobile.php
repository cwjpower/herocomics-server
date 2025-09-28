<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

function logp($m){ echo $m, "\n"; }

$root      = "/var/www/html";
$mobileDir = "$root/web/mobile";
$incDir    = "$root/web/inc/functions";
$cmsStub   = "$root/includes/functions/functions-cms-stubs.php";

@mkdir($incDir, 0775, true);

// 0) mobile/index.php: unserialize(NULL) ??
$idx = "$mobileDir/index.php";
if (is_file($idx)) {
  $c = file_get_contents($idx);
  if ($c!==false) {
    $new = preg_replace("/unserialize\s*\(/", "unserialize((string)", $c, -1, $cnt);
    if ($new!==null && $new!==$c) { file_put_contents($idx, $new); logp("[PATCH] unserialize cast ($cnt)"); }
  }
}

// 1) ?? ??: /web/mobile/*.php
$scan = [];
foreach (glob("$mobileDir/*.php") as $f) $scan[] = $f;

// 2) include/require ?? ?? ? inc/functions/*.php ???
$needInc = [];
$needFns = [];
$reInc1 = '/(?:require|include)(?:_once)?\s*\(?\s*(?:INC_PATH\s*\.\s*)?[\'"]([^\'"]*functions\/[^\'"]+\.php)[\'"]/i';
$reInc2 = '/(?:require|include)(?:_once)?\s*\(?\s*[\'"]([^\'"]*inc\/functions\/[^\'"]+\.php)[\'"]/i';
$reFn   = '/\b(lps_[a-zA-Z0-9_]+)\s*\(/';

foreach ($scan as $file) {
  $s = @file_get_contents($file);
  if ($s===false) continue;

  if (preg_match_all($reInc1, $s, $m)) foreach ($m[1] as $p) { $needInc[] = $p; }
  if (preg_match_all($reInc2, $s, $m)) foreach ($m[1] as $p) { $needInc[] = $p; }
  if (preg_match_all($reFn,   $s, $m)) foreach ($m[1] as $fn){ $needFns[] = $fn; }
}
$needInc = array_values(array_unique($needInc));
$needFns = array_values(array_unique($needFns));

// 3) ?? inc/functions ?? ?? ??(?? ??? ?? ??)
foreach ($needInc as $rel) {
  // functions/ ??? ??
  $pos = stripos($rel, "functions/");
  $name = $pos!==false ? substr($rel, $pos + strlen("functions/")) : basename($rel);
  $target = "$incDir/$name";
  $dir = dirname($target);
  if (!is_dir($dir)) @mkdir($dir, 0775, true);
  if (!is_file($target)) {
    $stub = "<?php\n/** auto-stub: $name */\n";
    if ($name === "functions-page.php") {
      $stub .= "if(!function_exists('e')){function e(\$s){return htmlspecialchars((string)\$s,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}}\n";
      $stub .= "if(!function_exists('page_header')){function page_header(\$t=''){echo \"<!doctype html><html lang='ko'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>\".e(\$t).\"</title></head><body>\";}}\n";
      $stub .= "if(!function_exists('page_footer')){function page_footer(){echo \"</body></html>\";}}\n";
    } elseif ($name === "functions-book.php") {
      $stub .= "if(!function_exists('book_cover_url')){function book_cover_url(array \$b){return \$b['cover']??(defined('IMG_URL')?IMG_URL.'/no-cover.png':'');}}\n";
      $stub .= "if(!function_exists('book_detail_url')){function book_detail_url(array \$b){if(isset(\$b['url']))return \$b['url'];\$id=\$b['id']??null;return \$id?((defined('WEB_URL')?WEB_URL:'/web').'/book.php?id='.\$id):'#';}}\n";
      $stub .= "if(!function_exists('book_title')){function book_title(array \$b){return \$b['title']??'????';}}\n";
      $stub .= "if(!function_exists('book_author')){function book_author(array \$b){return \$b['author']??'';}}\n";
    }
    file_put_contents($target, $stub);
    logp("[CREATE] $target");
  }
}

// 4) lps_* ?? ?? ?? ??
$cms = is_file($cmsStub) ? file_get_contents($cmsStub) : "<?php\n";
$added = 0;
foreach ($needFns as $fn) {
  if (strpos($cms, "function $fn(") !== false) continue;
  // ?? ??: ?? ??(?? ???)
  $cms .= "if(!function_exists('$fn')){function $fn(...\$args){return [];}}\n";
  $added++;
}
if ($added>0) {
  file_put_contents($cmsStub, $cms);
  logp("[ADD] lps_* stubs: $added");
}

// 5) ?????? ??? ??
$imgDir = "$mobileDir/img";
@mkdir($imgDir, 0775, true);
$png = base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAEklEQVR4nGP4z8DAwMAAAQYBAeKz0cIAAAAASUVORK5CYII=");
$files = ["no-cover.png","sample_a.jpg","sample_b.jpg","banner_demo.jpg","cur1.jpg","cur2.jpg","new1.jpg","hot1.jpg","book1.jpg","pbook1.jpg","publisher_demo.png"];
foreach ($files as $f) {
  $p = "$imgDir/$f";
  if (!is_file($p)) { file_put_contents($p, $png); logp("[IMG] $p"); }
}

// 6) BOM/???? ?? ??
function clean($p){
  $s=@file_get_contents($p); if($s===false) return;
  $o=$s;
  if(substr($s,0,3)==="\xEF\xBB\xBF") $s=substr($s,3);
  $s=preg_replace("/^\s+/u","",$s,1,$w);
  if(strpos($s,"<?php")!==0){
    $pos=strpos($s,"<?php");
    $s = ($pos===false) ? ("<?php\n".$s) : substr($s,$pos);
  }
  if($s!==$o){ file_put_contents($p,$s); echo "[CLEAN] $p\n"; }
}
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
  if (strtolower($f->getExtension())==="php") clean($f->getPathname());
}

echo "== AUTO FIX DONE ==\n";
