<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once(__DIR__ . '/../../conf/db.php');

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['code' => 1, 'msg' => 'POST method required']);
    exit;
}

// 파라미터 확인
$volume_id = $_POST['volume_id'] ?? null;

if (!$volume_id) {
    echo json_encode(['code' => 1, 'msg' => 'volume_id required']);
    exit;
}

// 파일 확인
if (!isset($_FILES['pages_zip'])) {
    echo json_encode(['code' => 1, 'msg' => 'No file uploaded']);
    exit;
}

$file = $_FILES['pages_zip'];

// 파일 에러 체크
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['code' => 1, 'msg' => 'File upload error: ' . $file['error']]);
    exit;
}

// ZIP 파일 확인
$file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if ($file_extension !== 'zip') {
    echo json_encode(['code' => 1, 'msg' => 'Only ZIP files allowed']);
    exit;
}

// 파일 크기 체크 (100MB)
$max_size = 100 * 1024 * 1024; // 100MB
if ($file['size'] > $max_size) {
    echo json_encode(['code' => 1, 'msg' => 'File too large. Max 100MB']);
    exit;
}

// 업로드 폴더 경로
$base_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/volume_pages/';
$volume_dir = $base_dir . 'volume_' . $volume_id . '/';

// 폴더 생성
if (!is_dir($volume_dir)) {
    mkdir($volume_dir, 0777, true);
}

// ZIP 파일 임시 저장
$temp_zip = $volume_dir . 'temp_' . time() . '.zip';
if (!move_uploaded_file($file['tmp_name'], $temp_zip)) {
    echo json_encode(['code' => 1, 'msg' => 'Failed to save ZIP file']);
    exit;
}

// ZIP 압축 해제
$zip = new ZipArchive;
if ($zip->open($temp_zip) === TRUE) {
    // 이전 페이지 이미지 삭제
    $old_files = glob($volume_dir . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
    foreach ($old_files as $old_file) {
        unlink($old_file);
    }

    // 새 파일 압축 해제
    $extracted_count = 0;
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $filename = $zip->getNameIndex($i);
        $file_info = pathinfo($filename);

        // 이미지 파일만 처리
        if (isset($file_info['extension'])) {
            $ext = strtolower($file_info['extension']);
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                // 파일명을 순서대로 변경 (page_001.jpg, page_002.jpg ...)
                $new_name = sprintf('page_%03d.%s', $extracted_count + 1, $ext);

                // 파일 추출
                $content = $zip->getFromIndex($i);
                file_put_contents($volume_dir . $new_name, $content);
                $extracted_count++;
            }
        }
    }

    $zip->close();
    unlink($temp_zip);

    // DB 업데이트 (페이지 수)
    try {
        $db = getDbConnection();
        $stmt = $db->prepare("UPDATE bt_books_volume SET page_count = ? WHERE volume_id = ?");
        $stmt->execute([$extracted_count, $volume_id]);

        echo json_encode([
            'code' => 0,
            'msg' => 'Upload success',
            'data' => [
                'volume_id' => $volume_id,
                'page_count' => $extracted_count,
                'folder' => 'volume_' . $volume_id
            ]
        ]);

    } catch (PDOException $e) {
        echo json_encode(['code' => 1, 'msg' => 'Database error: ' . $e->getMessage()]);
    }

} else {
    unlink($temp_zip);
    echo json_encode(['code' => 1, 'msg' => 'Failed to extract ZIP file']);
}
?>