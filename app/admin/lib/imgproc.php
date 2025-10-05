<?php
declare(strict_types=1);

function img_supported(string $fmt): bool {
  return ($fmt==='webp' && function_exists('imagewebp'))
      || ($fmt==='jpeg' && function_exists('imagejpeg'))
      || ($fmt==='png'  && function_exists('imagepng'));
}
function img_load(string $p) {
  $i=@getimagesize($p); if(!$i) return false;
  $m=$i["mime"]??""; return $m==="image/jpeg"?@imagecreatefromjpeg($p):
    ($m==="image/png"?@imagecreatefrompng($p):
    ($m==="image/gif"?@imagecreatefromgif($p):
    ($m==="image/webp"&&function_exists("imagecreatefromwebp")?@imagecreatefromwebp($p):false)));
}
function img_correct_orientation($im,string $p){
  if(function_exists("exif_read_data") && preg_match("/\\.(jpe?g)$/i",$p)){
    try{ $e=@exif_read_data($p); $o=(int)($e["Orientation"]??1);
      if($o===3) $im=imagerotate($im,180,0);
      elseif($o===6) $im=imagerotate($im,-90,0);
      elseif($o===8) $im=imagerotate($im,90,0);
    }catch(Throwable $e){}
  } return $im;
}
function img_resize_to_width($im,int $wmax){
  $w=imagesx($im); $h=imagesy($im); if($w<=$wmax) return $im;
  $nw=$wmax; $nh=(int)round($h*($nw/$w));
  $out=imagecreatetruecolor($nw,$nh);
  imagealphablending($out,false); imagesavealpha($out,true);
  $tc=imagecolorallocatealpha($out,0,0,0,127); imagefill($out,0,0,$tc);
  imagecopyresampled($out,$im,0,0,0,0,$nw,$nh,$w,$h); imagedestroy($im); return $out;
}
function img_save($im,string $dst,string $fmt,int $q=82): bool {
  $dir=dirname($dst); if(!is_dir($dir)) @mkdir($dir,0777,true);
  if($fmt==='webp'&&function_exists('imagewebp')) return @imagewebp($im,$dst,max(0,min(100,$q)));
  if($fmt==='jpeg'&&function_exists('imagejpeg')) return @imagejpeg($im,$dst,max(0,min(100,$q)));
  if($fmt==='png' &&function_exists('imagepng')) { $lvl=(int)round((100-max(0,min(100,$q)))*9/100); return @imagepng($im,$dst,$lvl); }
  return false;
}
function img_dims($im): array { return [imagesx($im),imagesy($im)]; }

/** return ['page_path','thumb_path','w','h'] */
function img_process_all(string $origAbs,string $baseDir,int $maxW,int $thumbW,string $pref,int $q=82): array {
  $im=img_load($origAbs); if(!$im) throw new RuntimeException("load fail: $origAbs");
  $im=img_correct_orientation($im,$origAbs);
  $fmt=img_supported($pref)?$pref:(img_supported('jpeg')?'jpeg':'png');

  $pageIm=img_resize_to_width($im,$maxW); [$w,$h]=img_dims($pageIm);
  $fname=basename($origAbs);
  if(preg_match('/^(\\d{4})\\.[a-z0-9]+$/i',$fname,$m)) $stem=$m[1];
  else { $stem=preg_replace('/\\D+/','',pathinfo($fname,PATHINFO_FILENAME)); if($stem==='') $stem='0001'; $stem=str_pad(substr($stem,-4),4,'0',STR_PAD_LEFT); }

  $pageRel="pages/{$stem}.{$fmt}"; $thumbRel="thumbs/{$stem}.{$fmt}";
  $pageAbs=rtrim($baseDir,'/')."/".$pageRel; $thumbAbs=rtrim($baseDir,'/')."/".$thumbRel;

  if(!img_save($pageIm,$pageAbs,$fmt,$q)) throw new RuntimeException("save page fail");
  $reload=img_load($pageAbs); $thumbIm=img_resize_to_width($reload,$thumbW);
  if(!img_save($thumbIm,$thumbAbs,$fmt,$q)) throw new RuntimeException("save thumb fail");

  return ['page_path'=>$pageAbs,'thumb_path'=>$thumbAbs,'w'=>$w,'h'=>$h];
}