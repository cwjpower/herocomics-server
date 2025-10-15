<?php
require_once '../../wps-config.php';

session_start();
if (!isset($_SESSION['login']['userid']) || $_SESSION['login']['user_level'] != 7) {
    header('Location: ../login.php');
    exit;
}

$publisher_id = $_SESSION['login']['publisher_id'];

// DB 연결
global $wdb;

// 내 책 목록 가져오기
$query = "SELECT * FROM bt_books WHERE publisher_id = ? ORDER BY created_dt DESC";
$stmt = $wdb->prepare($query);
$stmt->bind_param("i", $publisher_id);
$stmt->execute();
$result = $stmt->get_result();
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>내 책 목록 - Hero Comics</title>
        <style>
            body { font-family: 'Noto Sans KR', sans-serif; max-width: 1200px; margin: 50px auto; padding: 20px; }
            h1 { color: #333; }
            .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
            .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; }
            .btn:hover { background: #0056b3; }
            table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background: #f8f9fa; font-weight: bold; }
            img { max-width: 60px; height: auto; }
            .status-active { color: green; font-weight: bold; }
            .status-pending { color: orange; }
            .empty { text-align: center; padding: 60px 20px; color: #999; }
        </style>
    </head>
    <body>

    <div class="top-bar">
        <h1>📚 내 책 목록 (<?= htmlspecialchars($_SESSION['login']['display_name']) ?>)</h1>
        <div>
            <a href="book_upload.php" class="btn">➕ 새 책 업로드</a>
            <a href="../logout.php" class="btn" style="background: #dc3545;">🚪 로그아웃</a>
        </div>
    </div>

    <?php if($result->num_rows > 0): ?>
        <table>
            <thead>
            <tr>
                <th>표지</th>
                <th>제목</th>
                <th>저자</th>
                <th>정가</th>
                <th>할인율</th>
                <th>판매가</th>
                <th>무료</th>
                <th>상태</th>
                <th>등록일</th>
                <th>관리</th>
            </tr>
            </thead>
            <tbody>
            <?php while($book = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if($book['cover_img']): ?>
                            <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="표지">
                        <?php else: ?>
                            <div style="width:60px;height:80px;background:#eee;display:flex;align-items:center;justify-content:center;">📖</div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($book['book_title']) ?></strong></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= number_format($book['normal_price']) ?>원</td>
                    <td><?= $book['discount_rate'] ?>%</td>
                    <td><?= number_format($book['sale_price']) ?>원</td>
                    <td><?= $book['is_free'] == 'Y' ? '✅ 무료' : '-' ?></td>
                    <td>
                        <?php if($book['book_status'] == 1): ?>
                            <span class="status-active">✅ 판매중</span>
                        <?php else: ?>
                            <span class="status-pending">⏳ 대기</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('Y-m-d', strtotime($book['created_dt'])) ?></td>
                    <td>
                        <a href="book_edit.php?id=<?= $book['ID'] ?>">수정</a> |
                        <a href="book_delete.php?id=<?= $book['ID'] ?>" onclick="return confirm('정말 삭제하시겠습니까?')">삭제</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty">
            <h2>📭 아직 등록된 책이 없습니다</h2>
            <p>첫 번째 책을 업로드해보세요!</p>
            <br>
            <a href="book_upload.php" class="btn">첫 책 업로드하기</a>
        </div>
    <?php endif; ?>

    </body>
    </html>
<?php
$stmt->close();
?>