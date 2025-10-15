<?php
/**
 * HeroComics Admin Login API
 * 어드민 패널 로그인 처리
 *
 * POST /admin/apis/admin/login.php
 * 파라미터: email, password
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// OPTIONS 요청 처리 (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'code' => 1,
        'msg' => 'POST 요청만 허용됩니다.'
    ]);
    exit;
}

// DB 설정
$db_host = 'localhost';
$db_name = 'herocomics';
$db_user = 'root';
$db_pass = 'rootpass';

try {
    // DB 연결
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    echo json_encode([
        'code' => 1,
        'msg' => 'DB 연결 실패: ' . $e->getMessage()
    ]);
    exit;
}

// 입력값 받기
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 유효성 검사
if (empty($email) || empty($password)) {
    echo json_encode([
        'code' => 1,
        'msg' => '이메일과 비밀번호를 입력해주세요.'
    ]);
    exit;
}

// 이메일 형식 검사
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'code' => 1,
        'msg' => '올바른 이메일 형식이 아닙니다.'
    ]);
    exit;
}

try {
    // 어드민 계정 조회
    $stmt = $pdo->prepare("
        SELECT 
            ID as uid,
            user_login,
            user_pass,
            user_email,
            display_name,
            user_level,
            user_status
        FROM bt_users 
        WHERE user_email = :email
        AND user_level = 'admin'
        LIMIT 1
    ");

    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // 사용자 없음
    if (!$user) {
        echo json_encode([
            'code' => 1,
            'msg' => '어드민 계정이 존재하지 않습니다.'
        ]);
        exit;
    }

    // 계정 상태 확인
    if ($user['user_status'] != 0) {
        echo json_encode([
            'code' => 1,
            'msg' => '비활성화된 계정입니다.'
        ]);
        exit;
    }

    // 비밀번호 확인
    // WordPress 방식: MD5(password) 또는 wp_hash_password()
    $is_valid_password = false;

    // MD5 방식 체크
    if ($user['user_pass'] === md5($password)) {
        $is_valid_password = true;
    }
    // wp_hash_password 방식 체크 (WordPress 표준)
    elseif (function_exists('wp_check_password')) {
        $is_valid_password = wp_check_password($password, $user['user_pass'], $user['uid']);
    }
    // 간단한 해시 체크
    elseif (password_verify($password, $user['user_pass'])) {
        $is_valid_password = true;
    }

    if (!$is_valid_password) {
        echo json_encode([
            'code' => 1,
            'msg' => '비밀번호가 일치하지 않습니다.'
        ]);
        exit;
    }

    // JWT 토큰 생성 (간단 버전)
    $token_data = [
        'uid' => $user['uid'],
        'email' => $user['user_email'],
        'level' => $user['user_level'],
        'time' => time()
    ];

    // Base64 인코딩
    $token = base64_encode(json_encode($token_data));

    // 또는 실제 JWT 사용 (composer require firebase/php-jwt 필요)
    // $token = JWT::encode($token_data, 'your-secret-key', 'HS256');

    // 로그인 시간 업데이트
    $update_stmt = $pdo->prepare("
        UPDATE bt_users 
        SET last_login = NOW() 
        WHERE ID = :uid
    ");
    $update_stmt->execute(['uid' => $user['uid']]);

    // 성공 응답
    echo json_encode([
        'code' => 0,
        'msg' => '로그인 성공',
        'token' => $token,
        'admin' => [
            'uid' => $user['uid'],
            'email' => $user['user_email'],
            'name' => $user['display_name'],
            'level' => $user['user_level']
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'code' => 1,
        'msg' => 'DB 오류: ' . $e->getMessage()
    ]);
    exit;
}
?>