<?php
require_once '../../wps-config.php';

session_start();
// 세션 형식 수정: book_list.php와 동일하게
if (!isset($_SESSION['login']['userid']) || $_SESSION['login']['user_level'] != 7) {
    die('접근 권한이 없습니다.');
}

$user_id = $_SESSION['login']['userid'];
$publisher_id = $_SESSION['login']['publisher_id'];

// 폼 데이터 받기
$book_title = $_POST['book_title'];
$author = $_POST['author'];
$isbn = $_POST['isbn'] ?? '';
$normal_price = (int)$_POST['normal_price'];
$discount_rate = (int)$_POST['discount_rate'];
$is_free = $_POST['is_free'];

// 판매가 계산
$sale_price = $normal_price * (100 - $discount_rate) / 100;

// 업로드 디렉토리
$upload_dir = '/var/www/html/uploads/books/' . $publisher_id . '/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// 표지 이미지 업로드
$cover_img = '';
if (isset($_FILES['cover_img']) && $_FILES['cover_img']['error'] == 0) {
    $cover_ext = pathinfo($_FILES['cover_img']['name'], PATHINFO_EXTENSION);
    $cover_name = uniqid() . '.' . $cover_ext;
    $cover_path = $upload_dir . $cover_name;
    move_uploaded_file($_FILES['cover_img']['tmp_name'], $cover_path);
    $cover_img = '/uploads/books/' . $publisher_id . '/' . $cover_name;
}

// ZIP 파일 업로드
$epub_path = '';
$epub_name = '';
if (isset($_FILES['book_zip']) && $_FILES['book_zip']['error'] == 0) {
    $original_zip = $_FILES['book_zip']['tmp_name'];

    // 임시 폴더 생성
    $temp_dir = $upload_dir . 'temp_' . uniqid() . '/';
    mkdir($temp_dir, 0755, true);

    // ZIP 압축 해제
    $zip = new ZipArchive;
    if ($zip->open($original_zip) !== TRUE) {
        deleteDirectory($temp_dir);
        die('❌ ZIP 파일을 열 수 없습니다!');
    }

    $zip->extractTo($temp_dir);
    $zip->close();

    // frame.avf 있는지 확인
    $avf_path = $temp_dir . 'frame.avf';
    $has_avf = file_exists($avf_path);

    // 🚀 frame.avf 없으면 자동 생성!
    if (!$has_avf) {
        // 이미지 파일들 찾기
        $imageFiles = scanImageFiles($temp_dir);

        if (count($imageFiles) == 0) {
            deleteDirectory($temp_dir);
            die('❌ ZIP 파일에 이미지가 없습니다!');
        }

        // frame.avf JSON 생성
        $avfContent = generateFrameAVFJSON($imageFiles, $temp_dir);
        file_put_contents($avf_path, $avfContent);

        echo "✅ frame.avf 자동 생성 완료! (이미지 " . count($imageFiles) . "개)<br>";
    } else {
        echo "✅ frame.avf 파일이 이미 존재합니다.<br>";
    }

    // 새로운 ZIP 파일 생성
    $zip_name = uniqid() . '.zip';
    $new_zip_path = $upload_dir . $zip_name;

    $new_zip = new ZipArchive;
    if ($new_zip->open($new_zip_path, ZipArchive::CREATE) !== TRUE) {
        deleteDirectory($temp_dir);
        die('❌ 새 ZIP 파일을 생성할 수 없습니다!');
    }

    // 모든 파일을 새 ZIP에 추가 (재귀)
    addFilesToZip($new_zip, $temp_dir, $temp_dir);
    $new_zip->close();

    // 임시 폴더 삭제
    deleteDirectory($temp_dir);

    $epub_path = '/uploads/books/' . $publisher_id . '/' . $zip_name;
    $epub_name = $book_title . '.zip';
}

// DB에 저장 - wps-config.php의 global $wdb 사용
global $wdb;

$sql = "INSERT INTO bt_books 
        (book_title, author, publisher, isbn, normal_price, discount_rate, sale_price, 
         cover_img, epub_path, epub_name, is_free, upload_type, book_status, 
         created_dt, user_id, publisher_id)
        VALUES (?, ?, (SELECT publisher_name FROM bt_publishers WHERE publisher_id = ?), 
                ?, ?, ?, ?, ?, ?, ?, ?, 'publisher', 1, NOW(), ?, ?)";

$stmt = $wdb->prepare($sql);
$stmt->bind_param("ssissiissssii",
    $book_title, $author, $publisher_id, $isbn, $normal_price, $discount_rate,
    $sale_price, $cover_img, $epub_path, $epub_name, $is_free, $user_id, $publisher_id);

if ($stmt->execute()) {
    echo "✅ 책이 성공적으로 업로드되었습니다!<br>";
    echo "<a href='book_list.php'>내 책 목록으로 이동</a>";
} else {
    echo "❌ 업로드 실패: " . $wdb->error;
}

$stmt->close();

// ==================== 함수들 ====================

/**
 * 폴더에서 이미지 파일 스캔 (재귀)
 */
function scanImageFiles($dir) {
    $imageFiles = [];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!is_dir($dir)) {
        return $imageFiles;
    }

    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === 'frame.avf') continue;

        $fullPath = $dir . DIRECTORY_SEPARATOR . $file;

        if (is_dir($fullPath)) {
            // 하위 폴더도 스캔
            $subFiles = scanImageFiles($fullPath);
            $imageFiles = array_merge($imageFiles, $subFiles);
        } else {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $allowedExtensions)) {
                $imageFiles[] = $fullPath;
            }
        }
    }

    // 파일명으로 정렬 (숫자 순서)
    natsort($imageFiles);
    return array_values($imageFiles);
}

/**
 * frame.avf JSON 자동 생성 (액션 뷰어 형식)
 */
function generateFrameAVFJSON($imageFiles, $baseDir) {
    $pages = [];

    foreach ($imageFiles as $fullPath) {
        // 이미지 크기 가져오기
        $imageInfo = @getimagesize($fullPath);
        if ($imageInfo === false) {
            continue;
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // 상대 경로로 변환
        $relativePath = str_replace($baseDir, '', $fullPath);
        $relativePath = str_replace('\\', '/', $relativePath);
        $relativePath = ltrim($relativePath, '/');

        // 파일명에서 확장자 제거
        $nameWithoutExt = pathinfo($relativePath, PATHINFO_FILENAME);

        // 각 페이지는 일단 전체 화면 1개 프레임만 (기본 버전)
        $page = [
            'name' => $nameWithoutExt,
            'frames' => [
                [
                    'x' => 0,
                    'y' => 0,
                    'right' => $width,
                    'bottom' => $height
                ]
            ]
        ];

        $pages[] = $page;
    }

    // JSON으로 변환 (보기 좋게 포맷팅)
    return json_encode($pages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

/**
 * 폴더의 모든 파일을 ZIP에 추가 (재귀)
 */
function addFilesToZip($zip, $dir, $baseDir) {
    $files = scandir($dir);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;

        $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
        $relativePath = str_replace($baseDir, '', $fullPath);
        $relativePath = str_replace('\\', '/', $relativePath);
        $relativePath = ltrim($relativePath, '/');

        if (is_dir($fullPath)) {
            // 하위 폴더 재귀 처리
            addFilesToZip($zip, $fullPath, $baseDir);
        } else {
            // 파일 추가
            $zip->addFile($fullPath, $relativePath);
        }
    }
}

/**
 * 디렉토리 전체 삭제 (재귀)
 */
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