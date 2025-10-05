<?php
declare(strict_types=1);
/**
 * - INSERT/UPDATE/DELETE/DDL 등 차단(403)
 * - SELECT/SHOW/DESCRIBE/EXPLAIN에 LIMIT 없으면 LIMIT 200 부여
 *   → superglobal($_GET/$_POST) 값을 직접 덮어써서 기존 코드와 상관없이 적용
 */
function apply_readonly_guard(): void {
  $sql = $_POST["sql"] ?? $_GET["sql"] ?? "";
  if ($sql==="") return;

  // 주석 제거(간단)
  $q = preg_replace("/--.*$/m","",$sql);
  $q = preg_replace("/#.*$/m","",$q);
  $q = preg_replace("/\/\*.*?\*\//s","",$q);
  $q = trim($q);

  // 금지 키워드
  if (preg_match("/\b(insert|update|delete|replace|merge|drop|alter|truncate|create|grant|revoke|lock|unlock|call|set|use)\b/i",$q)) {
    http_response_code(403);
    exit("이 도구는 읽기전용입니다.");
  }

  // LIMIT 자동 부여
  if (preg_match("/^\s*(select|show|describe|explain)\b/i",$q) && !preg_match("/\blimit\s+\d+/i",$q)) {
    $q .= " LIMIT 200";
  }

  // superglobal 덮어쓰기(기존 코드가 어디서 읽든 동일효과)
  if (isset($_POST["sql"])) $_POST["sql"] = $q;
  if (isset($_GET["sql"]))  $_GET["sql"]  = $q;
  $_REQUEST["sql"] = $q;
}
apply_readonly_guard();
