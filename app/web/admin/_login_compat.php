<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$u = trim($_POST["username"] ?? $_POST["email"] ?? "");
$p = (string)($_POST["password"] ?? $_POST["pwd"] ?? $_POST["pass"] ?? $_POST["passwd"] ?? "");

if ($u === "" || $p === "") {
    http_response_code(400);
    exit("아이디/비밀번호를 입력해줘");
}

try {
    $pdo = new PDO(
        "mysql:host=mariadb;dbname=herocomics;charset=utf8mb4",
        "hero","secret",
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
    );

    // 테이블/컬럼 후보(레거시 호환)
    $tables = ["admin_users","admin_user"];
    $nameCols = ["username","email"];
    $passCols = ["password_hash","password","pwd","pass","passwd"];

    $user = null;
    foreach ($tables as $t) {
        // 테이블 없으면 스킵
        $st = $pdo->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=?");
        $st->execute([$t]);
        if (!$st->fetchColumn()) continue;

        foreach ($nameCols as $nc) {
            // 컬럼 없으면 스킵
            $st = $pdo->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=? AND column_name=?");
            $st->execute([$t,$nc]);
            if (!$st->fetchColumn()) continue;

            $sql = "SELECT * FROM `$t` WHERE `$nc`=? LIMIT 1";
            $st = $pdo->prepare($sql);
            $st->execute([$u]);
            $row = $st->fetch();
            if ($row) { $user = ["table"=>$t,"nameCol"=>$nc,"row"=>$row]; break 2; }
        }
    }

    if (!$user) { fail(); }

    $row = $user["row"];

    // 비밀번호 비교 (bcrypt > md5 > 평문)
    $ok = false;
    foreach ($passCols as $pc) {
        if (!array_key_exists($pc,$row)) continue;
        $val = (string)$row[$pc];

        if ($val === "") continue;

        // bcrypt 추정
        if (preg_match("/^\$2[ayb]\$/", $val)) {
            if (password_verify($p, $val)) { $ok = true; break; }
        }
        // md5 해시 길이
        if (!$ok && strlen($val) === 32 && ctype_xdigit($val)) {
            if (hash_equals(strtolower($val), md5($p))) { $ok = true; break; }
        }
        // 평문
        if (!$ok && $p === $val) { $ok = true; break; }
    }

    if (!$ok && array_key_exists("password_hash",$row)) {
        // 최후: password_hash가 있으면 여기로도 한 번 더 시도
        if (password_verify($p, (string)$row["password_hash"])) { $ok = true; }
    }

    if (!$ok) { fail(); }

    // 세션 3종 호환 세팅
    $id = (int)($row["id"] ?? $row["user_id"] ?? 1);
    session_regenerate_id(true);
    $_SESSION["ADMIN_USER_ID"]   = $id;
    $_SESSION["admin_id"]        = $id;
    $_SESSION["admin_logged_in"] = true;

    header("Location: /admin/series.php"); // 원래 landing이 따로 있으면 거기로 변경
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    exit("로그인 오류: ".$e->getMessage());
}

function fail(): void {
    http_response_code(401);
    exit("아이디 또는 비밀번호가 올바르지 않습니다.");
}
