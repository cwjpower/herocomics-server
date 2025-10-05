<?php
function clean($p){
  $s=@file_get_contents($p); if($s===false) return;
  $o=$s;
  if(substr($s,0,3)==="\xEF\xBB\xBF") $s=substr($s,3);
  $s=preg_replace("/^\s+/u","",$s,1,$w);
  if(strpos($s,"<?php")!==0){
    $pos = strpos($s,"<?php");
    $s = ($pos===false) ? ("<?php\n".$s) : substr($s,$pos);
  }
  if($s!==$o){ file_put_contents($p,$s); echo "CLEAN $p\n"; }
}
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("/var/www/html", FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
  if (strtolower($f->getExtension())==="php") clean($f->getPathname());
}
