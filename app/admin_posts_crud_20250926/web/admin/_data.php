<?php
// 간단 JSON 스토리지
function data_dir(){ return ABS_PATH . "data"; }
function data_path($name){ return data_dir()."/{$name}.json"; }
function data_load($name){
  if (!is_dir(data_dir())) @mkdir(data_dir(),0775,true);
  $p=data_path($name);
  $j=is_readable($p)?json_decode(@file_get_contents($p),true):[];
  return is_array($j)?$j:[];
}
function data_save($name,$arr){
  if (!is_dir(data_dir())) @mkdir(data_dir(),0775,true);
  @file_put_contents(data_path($name), json_encode(array_values($arr), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}
function data_next_id($arr){
  $max=0; foreach($arr as $r){ if(isset($r["id"])) $max=max($max,(int)$r["id"]); } return $max+1;
}
