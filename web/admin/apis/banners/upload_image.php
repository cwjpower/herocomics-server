<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // 파일 체크
    if (!isset($_FILES['banner_image']) || $_FILES['banner_image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('이미지 파일을 업로드해주세요.');
    }

    $file = $_FILES['banner_image'];

    // 파일 크기 체크 (5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('파일 크기는 5MB 이하여야 합니다.');
    }

    // 이미지 파일 체크
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_types)) {
        throw new Exception('이미지 파일만 업로드 가능합니다. (jpg, png, gif, webp)');
    }

    // 업로드 폴더 생성
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/banners/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // 파일명 생성 (중복 방지)
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'banner_' . time() . '_' . uniqid() . '.' . $extension;
    $filepath = $upload_dir . $filename;

    // 파일 이동
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception('파일 업로드에 실패했습니다.');
    }

    // URL 생성
    $file_url = '/uploads/banners/' . $filename;

    echo json_encode([
        'success' => true,
        'message' => '이미지가 업로드되었습니다.',
        'file_url' => $file_url,
        'filename' => $filename
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}