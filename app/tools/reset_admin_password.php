<?php
declare(strict_types=1);
$pdo = new PDO("mysql:host=mariadb;dbname=herocomics;charset=utf8mb4","hero","secret",
  [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$user="admin"; $plain="123456"; $bcrypt=password_hash($plain,PASSWORD_BCRYPT); $md5=md5($plain);
function t(PDO $p,$q,$a){$st=$p->prepare($q);$st->execute($a);return $st;}
function tex(PDO $p,$t){return (bool)t($p,"SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=?",[$t])->fetchColumn();}
function cols(PDO $p,$t){$m=[];foreach(t($p,"SELECT COLUMN_NAME,COLUMN_TYPE FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=?",[$t]) as $r){$m[$r['COLUMN_NAME']]=$r['COLUMN_TYPE'];}return $m;}
foreach(["admin_users","admin_user"] as $tbl){
 if(!tex($pdo,$tbl)) continue; $C=cols($pdo,$tbl);
 $nameCol = $C["username"]??($C["email"]??null); if(!$nameCol) continue;
 $passCols = array_values(array_intersect(array_keys($C),["password_hash","password","pwd","pass","passwd"])); if(!$passCols) continue;
 $row=t($pdo,"SELECT * FROM `$tbl` WHERE `$nameCol`=? LIMIT 1",[$user])->fetch();
 $upd = [$nameCol=>$user]; if(!$row && isset($C["id"])) $upd["id"]=1;
 foreach($passCols as $pc){ $ctype=strtolower($C[$pc]??""); $len=preg_match("/char\((\d+)\)/",$ctype,$m)?(int)$m[1]:255; $upd[$pc]=($pc==="password_hash"||$len>=50)?$bcrypt:($len>=32?$md5:$plain); }
 if (isset($C["password_hash"])) $upd["password_hash"]=$bcrypt;
 $cols=array_keys($upd); $vals=array_values($upd);
 $place=implode(",",array_fill(0,count($cols),"?" )); $updSql=implode(",",array_map(fn($c)=>"$c=VALUES($c)",$cols));
 try{ t($pdo,"INSERT INTO `$tbl`(".implode(",",$cols).") VALUES($place) ON DUPLICATE KEY UPDATE $updSql",$vals); }
 catch(Throwable){ $set=implode(",",array_map(fn($c)=>"$c=?", $cols)); t($pdo,"UPDATE `$tbl` SET $set WHERE `$nameCol`=?",array_merge($vals,[$user])); }
 echo "[OK] $tbl reset\n";
}
echo "done\n";
