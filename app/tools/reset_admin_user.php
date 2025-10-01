<?php
declare(strict_types=1);
/**
 * 사용법:
 *   php /var/www/html/tools/reset_admin_user.php [username] [password]
 * 기본값: admin / 123456
 */
$u = $argv[1] ?? "admin";
$p = $argv[2] ?? "123456";

try {
  $pdo = new PDO(
    "mysql:host=mariadb;dbname=herocomics;charset=utf8mb4",
    "hero","secret",
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
  );

  // 1) 테이블 탐색
  $tables = ["admin_users","admin_user"];
  $table = null;
  foreach ($tables as $t) {
    $st=$pdo->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=?");
    $st->execute([$t]); if($st->fetchColumn()) { $table=$t; break; }
  }
  if(!$table) { throw new RuntimeException("admin_users/admin_user 테이블이 없음"); }

  // 2) 컬럼 파악
  $cols = [];
  foreach ($pdo->query("SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=".$pdo->quote($table)) as $r) {
    $cols[$r["COLUMN_NAME"]] = true;
  }
  $idCol   = isset($cols["id"])?"id":(isset($cols["user_id"])?"user_id":null);
  $nameCol = isset($cols["username"])?"username":(isset($cols["email"])?"email":null);
  if(!$nameCol){ throw new RuntimeException("username/email 컬럼이 필요"); }

  $passCols = array_values(array_intersect(array_keys($cols), ["password_hash","password_md5","password","pwd","pass","passwd"]));
  if (!$passCols) { $passCols = []; } // 없을 수도 있음(최소 password_hash만 만들 예정)

  // 3) 값 준비 (현대식 + 레거시 동시 세팅)
  $bcrypt = password_hash($p, PASSWORD_BCRYPT);
  $md5    = md5($p);

  $set = [$nameCol => $u];
  if (isset($cols["password_hash"])) $set["password_hash"] = $bcrypt;
  if (isset($cols["password_md5"]))  $set["password_md5"]  = $md5;
  foreach (["password","pwd","pass","passwd"] as $pc) {
    if (isset($cols[$pc])) { $set[$pc] = $p; } // 평문 컬럼엔 평문 저장(우리 auth.php가 평문도 허용)
  }

  // password_hash 컬럼이 아예 없더라도, 있으면 채우도록 시도
  if (!isset($cols["password_hash"])) {
    // 컬럼이 없으면 스킵(레거시만 있는 경우)
  }

  // 4) 존재 여부 확인
  $st=$pdo->prepare("SELECT ".($idCol? "`$idCol`" : "1")." FROM `$table` WHERE `$nameCol`=? LIMIT 1");
  $st->execute([$u]);
  $row=$st->fetch();

  if ($row) {
    // UPDATE
    $sets=[]; $vals=[];
    foreach($set as $c=>$v){ $sets[]="`$c`=?"; $vals[]=$v; }
    if ($idCol) {
      $id = (int)$row[$idCol];
      $vals[] = $id;
      $sql="UPDATE `$table` SET ".implode(",",$sets)." WHERE `$idCol`=?";
      $pdo->prepare($sql)->execute($vals);
      echo "UPDATED $table id=$id user=$u\n";
    } else {
      $vals[] = $u;
      $sql="UPDATE `$table` SET ".implode(",",$sets)." WHERE `$nameCol`=?";
      $pdo->prepare($sql)->execute($vals);
      echo "UPDATED $table user=$u\n";
    }
  } else {
    // INSERT (없는 컬럼은 제외)
    $colsIns = array_keys($set);
    $valsIns = array_values($set);
    $ph = implode(",", array_fill(0, count($colsIns), "?"));
    $sql = "INSERT INTO `$table`(".implode(",", array_map(fn($c)=>"`$c`",$colsIns)).") VALUES($ph)";
    $pdo->prepare($sql)->execute($valsIns);
    $id = (int)$pdo->lastInsertId();
    echo "INSERTED $table id=$id user=$u\n";
  }

  echo "OK: username=$u / password=$p\n";
  exit(0);
} catch(Throwable $e) {
  fwrite(STDERR, "ERROR: ".$e->getMessage()."\n");
  exit(1);
}
