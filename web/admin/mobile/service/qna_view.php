<?php
/*
 * Desc : 1:1 문의 보기
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-term.php';

if ( empty($_GET['pid'] )) {
	lps_alert_back( '게시글의 아이디가 존재하지 않습니다.' );
}
$post_id = $_GET['pid'];

$post_row = wps_get_post_qnas( $post_id );
$post_type = $post_row['post_type'];

$post_title = htmlspecialchars( $post_row['post_title'] );
$post_content = $post_row['post_content'];

$post_ans_title = htmlspecialchars( $post_row['post_ans_title'] );
$post_ans_content = $post_row['post_ans_content'];

$post_user_id = $post_row['post_user_id'];
$post_date = $post_row['post_date'];
$post_ans_date = $post_row['post_ans_date'];

$post_term_id = $post_row['post_term_id'];
$term_name = wps_get_term_name($post_term_id);

$attachment = @$post_row['post_attachment'];

if (!empty($attachment)) {
	$attachment = unserialize($attachment);
	$attachment_link = '<a href="' . INC_URL . '/lib/download-qnas-attachment.php?pid=' . $post_id . '&key=0" class="label label-info">'. $attachment[0]['file_name'] .'</a>';
} else {
	$attachment_link = '';
}

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content service-area">
				<div class="sevice-menu-area">
					<div data-role="navbar">
						<ul class="sevice-menu">
							<li><a href="<?php echo MOBILE_URL ?>/service/cs.php" data-ajax="false"><img src="../img/service/service_m01_off.png" alt="">고객센터 안내</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/notice_list.php" data-ajax="false"><img src="../img/service/service_m02_off.png" title="공지사항">공지사항</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/faq.php" data-ajax="false"><img src="../img/service/service_m03_off.png" title="자주 묻는 질문">자주 묻는 질문</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/qna.php" data-ajax="false"><img src="../img/service/service_m04_on.png" title="1:1 문의">1:1 문의</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/ask_book.php" data-ajax="false"><img src="../img/service/service_m05_off.png" title="도서 신청">도서 신청</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/feedback.php" data-ajax="false"><img src="../img/service/service_m06_off.png" title="건의사항">건의사항</a></li>
						</ul>
					</div>
				</div>
				<div class="sevice-conts">
					<h2>1:1 문의</h2>
					<div>
						<div class="answer-area">
							<ul>
								<li><strong>Q : [<?php echo $term_name ?>] <?php echo $post_title ?></strong></li>
								<li class="answer-date"><?php echo $post_date ?></li>
								<li class="answer-txt-box user-q-txt"><?php echo nl2br($post_content) ?></li>
								<li style="margin: 5px 0;"><?php echo $attachment_link ?></li>
							</ul>
							<div class="answer-line"></div>
							<ul>
								<li><strong><span class="txt-f26061">A</span> : <?php echo $post_ans_title ?></strong></li>
								<li class="answer-date"><?php echo $post_ans_date ?></li>
								<li class="answer-txt-box"><?php echo $post_ans_content ?></li>
							</ul>
						</div>
						<div><a href="qna_list.php" data-ajax="false" class="btn-skyblue">확인</a></div>
					</div>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
