<?php
function clean($p){
  if(!is_file($p)) return;
  $s=file_get_contents($p); if($s===false) return;
  if(substr($s,0,3)==="\xEF\xBB\xBF") $s=substr($s,3);
  $s=preg_replace("/^\s+/u","",$s,1,$w);
  if(strpos($s,"<?php")!==0){
    $pos=strpos($s,"<?php");
    $s = ($pos===false) ? ("<?php\n".$s) : substr($s,$pos);
  }
  file_put_contents($p,$s);
  echo "CLEAN $p\n";
}
clean("/var/www/html/includes/functions/functions-wps.php");
clean("/var/www/html/web/mobile/functions.php");
clean("/var/www/html/includes/functions/functions-cms-stubs.php");
clean("/var/www/html/wps-vars.php");
