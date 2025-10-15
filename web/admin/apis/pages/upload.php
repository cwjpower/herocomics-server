<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['code' => 1, 'msg' => 'POST 요청만 허용됩니다.']);
    exit;
}

$db_host = 'herocomics-mariadb';
$db_name = 'herocomics';
$db_user = 'root';
$db_pass = 'rootpass';

try {
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $volume_id = $_POST['volume_id'] ?? 0;

    if (empty($volume_id)) {
        echo json_encode(['code' => 1, 'msg' => 'volume_id가 필요합니다.']);
        exit;
    }

    // 볼륨 정보 확인
    $stmt = $pdo->prepare("SELECT series_id FROM bt_volumes WHERE volume_id = ?");
    $stmt->execute([$volume_id]);
    $volume = $stmt->fetch();

    if (!$volume) {
        echo json_encode(['code' => 1, 'msg' => '볼륨을 찾을 수 없습니다.']);
        exit;
    }

    $series_id = $volume['series_id'];

    // 업로드 디렉토리 설정
    $upload_dir = "/var/www/html/uploads/series_{$series_id}/volume_{$volume_id}/";
    $temp_extract_dir = "/tmp/extract_" . time() . "/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $uploaded_files = [];
    $uploaded_count = 0;

    // ZIP 파일 업로드 처리
    if (isset($_FILES['zip_file']) && $_FILES['zip_file']['error'] === UPLOAD_ERR_OK) {
        $zip_tmp_path = $_FILES['zip_file']['tmp_name'];
        $zip_name = $_FILES['zip_file']['name'];

        // ZIP 파일인지 확인
        $ext = strtolower(pathinfo($zip_name, PATHINFO_EXTENSION));
        if ($ext !== 'zip') {
            echo json_encode(['code' => 1, 'msg' => 'ZIP 파일만 업로드 가능합니다.']);
            exit;
        }

        // ZipArchive 확장이 설치되어 있는지 확인
        if (!class_exists('ZipArchive')) {
            echo json_encode(['code' => 1, 'msg' => 'ZIP 확장이 설치되지 않았습니다.']);
            exit;
        }

        $zip = new ZipArchive;

        if ($zip->open($zip_tmp_path) === TRUE) {
            // 임시 압축 해제 디렉토리 생성
            if (!file_exists($temp_extract_dir)) {
                mkdir($temp_extract_dir, 0777, true);
            }

            // ZIP 압축 해제
            $zip->extractTo($temp_extract_dir);
            $zip->close();

            // 압축 해제된 파일들 가져오기
            $extracted_files = [];
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($temp_extract_dir)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $filepath = $file->getPathname();
                    $filename = $file->getFilename();

                    // 숨김 파일 제외 (__MACOSX, .DS_Store 등)
                    if (strpos($filename, '.') === 0 || strpos($filepath, '__MACOSX') !== false) {
                        continue;
                    }

                    // 이미지 파일만 필터링
                    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                    if (in_array($file_ext, $allowed_ext)) {
                        $extracted_files[] = [
                            'path' => $filepath,
                            'name' => $filename,
                            'ext' => $file_ext,
                            'size' => filesize($filepath)
                        ];
                    }
                }
            }

            // 파일명 순서대로 정렬 (001.jpg, 002.jpg...)
            usort($extracted_files, function($a, $b) {
                return strnatcasecmp($a['name'], $b['name']);
            });

            // 현재 최대 페이지 번호 확인
            $max_page_stmt = $pdo->prepare("
                SELECT COALESCE(MAX(page_number), 0) as max_page 
                FROM bt_pages 
                WHERE volume_id = ?
            ");
            $max_page_stmt->execute([$volume_id]);
            $result = $max_page_stmt->fetch();
            $current_max_page = $result['max_page'];

            // 파일들을 순서대로 저장
            foreach ($extracted_files as $index => $file) {
                $page_number = $current_max_page + $index + 1;

                // 새 파일명 생성 (페이지 번호 포함)
                $new_filename = sprintf('page_%03d.%s', $page_number, $file['ext']);
                $upload_path = $upload_dir . $new_filename;

                // 파일 복사
                if (copy($file['path'], $upload_path)) {
                    chmod($upload_path, 0644);

                    // DB에 저장
                    $web_path = "/uploads/series_{$series_id}/volume_{$volume_id}/{$new_filename}";

                    $insert = $pdo->prepare("
                        INSERT INTO bt_pages 
                        (volume_id, page_number, image_url, file_size, created_at)
                        VALUES (?, ?, ?, ?, NOW())
                    ");

                    $insert->execute([
                        $volume_id,
                        $page_number,
                        $web_path,
                        $file['size']
                    ]);

                    $uploaded_files[] = [
                        'page_id' => $pdo->lastInsertId(),
                        'page_number' => $page_number,
                        'image_url' => $web_path,
                        'original_name' => $file['name']
                    ];

                    $uploaded_count++;
                }
            }

            // 볼륨의 총 페이지 수 업데이트
            $update_volume = $pdo->prepare("
                UPDATE bt_volumes 
                SET total_pages = (
                    SELECT COUNT(*) FROM bt_pages WHERE volume_id = ?
                )
                WHERE volume_id = ?
            ");
            $update_volume->execute([$volume_id, $volume_id]);

            // 임시 디렉토리 삭제
            deleteDirectory($temp_extract_dir);

            echo json_encode([
                'code' => 0,
                'msg' => 'ZIP 파일 업로드 성공',
                'data' => [
                    'uploaded_count' => $uploaded_count,
                    'total_pages' => $current_max_page + $uploaded_count,
                    'files' => $uploaded_files
                ]
            ]);

        } else {
            echo json_encode(['code' => 1, 'msg' => 'ZIP 파일을 열 수 없습니다.']);
        }

    } else {
        $error_msg = '파일 업로드 오류';
        if (isset($_FILES['zip_file'])) {
            $error_code = $_FILES['zip_file']['error'];
            switch ($error_code) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $error_msg = '파일 크기가 너무 큽니다.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error_msg = '파일이 선택되지 않았습니다.';
                    break;
                default:
                    $error_msg = "업로드 오류 코드: {$error_code}";
            }
        }

        echo json_encode(['code' => 1, 'msg' => $error_msg]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'code' => 1,
        'msg' => 'DB 오류: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'code' => 1,
        'msg' => '오류: ' . $e->getMessage()
    ]);
}

// 디렉토리 삭제 함수
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}
?>