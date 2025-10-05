<?php
error_reporting(E_ALL);
ini_set("display_errors",1);

$roots = [
  "/var/www/html/web",
  "/var/www/html/web/mobile",
  "/var/www/html/web/inc",
];

function is_utf8_bytes($s){ return (bool)preg_match("//u", $s); }

function maybe_convert_to_utf8($path){
  $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
  if (!in_array($ext, ["php","html","htm","css","js"])) return;
  $s = @file_get_contents($path);
  if ($s===false) return;

  // ?? UTF-8 ?? ???
  if (is_utf8_bytes($s)) return;

  // ?? ??? ?(??? ?? ?? ???)
  $candidates = ["CP949","EUC-KR","EUC_KR","ISO-8859-1"];
  foreach ($candidates as $enc){
    $converted = @iconv($enc, "UTF-8//IGNORE", $s);
    if ($converted!==false && $converted!=="") {
      // ?? ? ?? UTF-8 ?? ??
      if (is_utf8_bytes($converted)) {
        // BOM ?? + ?? ?? ??
        if (substr($converted,0,3)==="\xEF\xBB\xBF") $converted = substr($converted,3);
        $converted = preg_replace("/^\xEF\xBB\xBF/u","",$converted);
        file_put_contents($path, $converted);
        echo "[CONVERT $enc->UTF-8] $path\n";
        return;
      }
    }
  }
}

function clean_bom_lead($path){
  $s=@file_get_contents($path); if($s===false) return;
  $o=$s;
  if (substr($s,0,3)==="\xEF\xBB\xBF") $s = substr($s,3);
  // ?? ??? ??/?? ??
  $s = preg_replace("/^\s+/u","",$s,1,$w);
  // ???? <?php ? ????? ??
  if (strpos($s,"<?php")!==0) {
    $pos = strpos($s,"<?php");
    if ($pos!==false) $s = substr($s,$pos);
  }
  if ($s!==$o) { file_put_contents($path,$s); echo "[CLEAN] $path\n"; }
}

foreach ($roots as $r){
  if (!is_dir($r)) continue;
  $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($r, FilesystemIterator::SKIP_DOTS));
  foreach ($it as $f){
    $p = $f->getPathname();
    maybe_convert_to_utf8($p);
    if (strtolower($f->getExtension())==="php") clean_bom_lead($p);
  }
}
echo "== UTF8 FIX DONE ==\n";
