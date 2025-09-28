<?php
declare(strict_types=1);
require_once __DIR__ . '/../wps-config.php';

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../wps-config.php';
require_once __DIR__ . '/../wps-settings.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-page.php';
require_once __DIR__ . "/functions.php";
require_once dirname(__DIR__) . "/functions/functions-book.php";



// ????癲ル슢??????轅붽틓????嚥????$todays_new = lps_get_todays_new_book(6);

// ??傭?끆??????猷???????筌믨껴逾?????from page?????怨멸텛???????鶯ㅺ동??ш낄援????????
$best_rank_book = wps_get_option( 'lps_best_rank_1000' );
$best_books = unserialize((string)(string)(string)(string)$best_rank_book);

// ??????⑤９??????for web
$notice_rows = lps_get_posts_by_type( 'notice_new', 3, 'web');

// ??????????????????⑤뜪??????獄쏅챶留??from page?????怨멸텛???admin
$publisher_rows = lps_get_publishers();

// ??????????源놁７??from page?????怨멸텛???admin
$curations = lps_get_main_curations();

// ?耀붾굝????????????밸븶筌믩끃?????濾?$banners = lps_get_front_banners();

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="?????獄쏅챶留??????? ?????????얠뺏??븍툙??? ??傭?끆??????猷???????筌믨껴逾? ???????뉙뀭???">
		<title>BOOK talk</title>
		<link rel="shortcut icon" href="favicon.ico">
		<!-- css -->
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/jquery.mobile-1.4.5.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/globals.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/main.css">
		<link rel="stylesheet" href="<?php echo INC_URL ?>/css/jquery.bxslider.min.css">
		<!-- js -->
		<script src="<?php echo INC_URL ?>/js/jquery.min.js"></script>
		<script src="<?php echo INC_URL ?>/js/jquery.mobile-1.4.5.min.js"></script>
		<script src="<?php echo INC_URL ?>/js/jquery.bxslider.min.js"></script>
		
		<script>
		$(function() {
			$('.bxslider').bxSlider({
				auto: true,
				autoControls: true
			});
		});
		</script>
		
		<style>
		.bx-pager, .bx-default-pager, .bx-controls-auto { display: none; }
		.bx-wrapper { margin: 0 auto; }
		</style>

	<script src="/admin/assets/newwin.js"></script>
</head>
	<body>
		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content main-area">
				<div class="bnr">
		<?php 
		if ( !empty($banners) ) {
		?>
					<ul class="bxslider">
				<?php 
				foreach ( $banners as $key => $val ) {
					$banner_id = $val['ID'];
					$banner_title = $val['bnr_title'];
					$banner_link = empty($val['bnr_url']) ? '#' : $val['bnr_url'];
					$target = $val['bnr_target'];
					$show = $val['hide_or_show'];
	// 				$bnr_from = $val['bnr_from'];
	// 				$bnr_to = $val['bnr_to'];
					
					$file_url = $val['bnr_file_url'];
					$file_name = $val['bnr_file_name'];
					if ( !strcmp($show, 'show') ) {
						$active = empty($key) ? 'active' : '';
			?>
						<li>
							<a href="<?php echo $banner_link ?>" target="<?php echo $target ?>">
								<img src="<?php echo $file_url ?>" border="0" title="<?php echo $file_name ?>">
							</a>
						</li>
				<?php 
					}
				}
				?>
					</ul>
		<?php 
		} else {
		?>
					<img style="width:100%;" src="<?php echo MOBILE_URL ?>/img/main/bnr_sample.jpg" alt="">
		<?php 
		}
		?>
				</div>
				<!-- /.bnr -->
				<div class="main-cont">
					<!-- newbook-list -->
					<div class="list-view-area">
						<h2 class="list-tit">????癲ル슢??????????影??젌??/h2>
						<ul class="main-newbook-list">
				<?php
				if ( !empty($todays_new) ) {
					foreach ( $todays_new as $key => $val ) {
						
						$book_rows = lps_get_book($val);
						
						$book_id = $book_rows['ID'];
						$publisher = $book_rows['publisher'];
						$book_title = $book_rows['book_title'];
						$author = $book_rows['author'];
						$cover_img = $book_rows['cover_img'];
						$created_dt = substr($book_rows['created_dt'], 0, 10);
						
				?>
							<li>
								<div class="booklist-img-l">
									<div class="book-img-box">
										<div class="book-opc"></div>
										<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false"><img class="book-img" src="<?php echo $cover_img ?>"></a>
									</div>
								</div>
								<div class="booklist-txt-r">
									<dl>
										<dt class="ellipsis"><a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false"><?php echo $book_title ?></a></dt>
										<dd class="ellipsis"><?php echo $author ?></dd>
									</dl>
								</div>
							</li>
				<?php
					}
				}
				?>
							
						</ul>
						<a href="<?php echo MOBILE_URL ?>/book/today_new.php" data-ajax="false" class="btn-more">?????獄쏅챶留????潁??????????/a>
					</div>
					<!-- newbook-list End -->
					<!-- community-ranking -->
					<div class="list-view-area">
						<h2 class="list-tit">??傭?끆??????猷???????筌믨껴逾?????/h2>
						<div class="community-bnr-area">
							<div class="com-bnr">
								<ul class="main-newbook-list">
						<?php
						// ???????살몝??10??						if ( !empty($best_books) ) {
							foreach ( $best_books as $key => $val ) {
								
								$book_rows = lps_get_book($val);
								
								$book_id = $book_rows['ID'];
								$publisher = $book_rows['publisher'];
								$book_title = $book_rows['book_title'];
								$author = $book_rows['author'];
								$cover_img = $book_rows['cover_img'];
								$created_dt = substr($book_rows['created_dt'], 0, 10);
								
						?>
									<li>
										<div class="booklist-img-l">
											<div class="book-img-box">
												<div class="book-opc"></div>
												<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false"><img class="book-img" src="<?php echo $cover_img ?>" title="?????"></a>
											</div>
										</div>
										<div class="booklist-txt-r">
											<dl>
												<dt class="ellipsis"><a href="#"><?php echo $book_title ?></a></dt>
												<dd class="ellipsis"><?php echo $author ?></dd>
											</dl>
										</div>
									</li>
						<?php

						}
						?>
									
								</ul>
							</div>
						</div>
						<a href="<?php echo MOBILE_URL ?>/book/community_rank.php" data-ajax="false" class="btn-more">?????獄쏅챶留????潁??????????/a>
					</div>
					<!-- community-ranking End -->
					<!-- publisher-book -->
					<div class="list-view-area">
						<h2 class="list-tit">??????????????????⑤뜪??????獄쏅챶留??/h2>
						<ul class="main-newbook-list">
				<?php
				if ( !empty($publisher_rows) ) {
					foreach ( $publisher_rows as $key => $val ) {
// 						$pb_id = $val['ID'];
						$publisher_id = $val['publisher_id'];
						
						$users = wps_get_user_by('ID', $publisher_id);
						$user_name = $users['user_name'];
						$avatar = lps_get_user_avatar($publisher_id);
				?>
							<li>
								<div class="booklist-img-l">
									<div class="book-img-box">
										<div class="book-opc"></div>
										<a href="<?php echo MOBILE_URL ?>/book/publisher_book.php?id=<?php echo $publisher_id ?>" data-ajax="false"><img class="book-img" src="<?php echo $avatar ?>"></a>
									</div>
								</div>
								<div class="booklist-txt-r">
									<dl>
										<dt class="ellipsis"><a href="<?php echo MOBILE_URL ?>/book/publisher_book.php?id=<?php echo $publisher_id ?>" data-ajax="false"><?php echo $user_name ?></a></dt>
									</dl>
								</div>
							</li>
							
							<!-- li>
								<div class="booklist-img-l">
									<div class="book-img-box">
										<div class="book-opc"></div>
										<a href="#"><img class="book-img" src="../img/book_img/book_sample01.jpg" alt=""></a>
									</div>
								</div>
								<div class="booklist-txt-r">
									<div class="main-publisher-name"><a href="#">???癲???????꿔꺂?€??</a></div>
								</div>
							</li> -->
				<?php 
					}
				}
				?>
						</ul>
						<a href="<?php echo MOBILE_URL ?>/book/publisher.php" data-ajax="false" class="btn-more">?????獄쏅챶留????潁??????????/a>
					</div>
					<!-- publisher-book End -->
					<!-- curating -->
					<div class="list-view-area">
						<h2 class="list-tit">???????살퓢?????????????諛몃마???틙??/h2>
						<ul class="main-newbook-list">
				<?php
				if ( !empty($curations) ) {
					foreach ( $curations as $key => $val ) {
						$curation_id = $val['ID'];
						$curation_title = $val['curation_title'];
						$cover_img = $val['cover_img'];
						$curation_order = $val['curation_order'];
						$curation_status = $val['curation_status'];
						$curator_level = $val['curator_level'];
						$curation_meta = $val['curation_meta'];
						$user_id = $val['user_id'];
						$created_dt = $val['created_dt'];
						
						$book_count = count(unserialize((string)(string)(string)(string)$curation_meta));
						
						$cover_file = unserialize((string)(string)(string)(string)$cover_img);
						
						$file_url = $cover_file['file_url'];
						$file_name = $cover_file['file_name'];
						
						$users = wps_get_user_by('ID', $user_id);
						$user_name = $users['user_name'];

				?>
							<li>
								<div class="booklist-img-l">
									<div class="book-img-box">
										<div class="book-opc"></div>
										<a href="./book/curation_view.php?id=<?php echo $curation_id ?>" data-ajax="false"><img class="book-img" src="<?php echo $file_url ?>" title="<?php echo $file_name ?>"></a>
									</div>
								</div>
								<div class="booklist-txt-r">
									<div class="main-curating-name"><a href="#"><?php echo $curation_title ?></a></div>
								</div>
							</li>
				<?php
					}
				}
				?>
						</ul>
						<a href="<?php echo MOBILE_URL ?>/book/curation_list.php" data-ajax="false" class="btn-more">?????獄쏅챶留????潁??????????/a>
					</div>
					<!-- curating End -->
					<!-- notice -->
					<div class="list-view-area">
						<h2 class="list-tit">??????⑤９??????/h2>
						<ul class="notice-list">
				<?php 
				if (!empty($notice_rows)) {
					foreach ($notice_rows as $key => $val) {
						$post_id = $val['ID'];
						$post_title = htmlspecialchars($val['post_title']);
				?>
							<li><a href="<?php echo MOBILE_URL ?>/service/notice_view.php?pid=<?php echo $post_id ?>" data-ajax="false"><?php echo $post_title ?></a></li>
				<?php 
					}
				}
				?>
						</ul>
						<a href="<?php echo MOBILE_URL ?>/service/notice_list.php" data-ajax="false" class="btn-more">?????獄쏅챶留????潁??????????/a>
					</div>
					<!-- notice End -->
				</div>
			</div>
			<!-- main End -->
			<!-- footer -->
			<div data-role="footer">
				<div>
					<a href="<?php echo MOBILE_URL ?>/service/terms.php" data-ajax="false">???????ㅻ쑄????</a>
					<span class="f-bar">l</span>
					<a href="<?php echo MOBILE_URL ?>/service/privacy.php" data-ajax="false">?????ル뒌?????饔낅떽???????????????????ш끽維뽳쭩?뱀땡??/a>
					<span class="f-bar">l</span>
					<a href="<?php echo MOBILE_URL ?>/service/cs.php" data-ajax="false">?????????????????/a>
					<span class="f-bar">l</span>
					<a href="#">App????μ떜媛?걫??</a>
				</div>
				<div><span>???轅붽틓?????얜?異???耀붾굝??????雅?퍔瑗?땟????룸챸???怨뚰뇞??????椰?????????癲ル슢???с궘?396 11??1103??(???????뉙뀭?欲꼲????⑤슦瑗ο┼?????沃섃뫚?????轅붽틓??????</span></div>
				<!-- div><span class="f-bar">l</span> -->
				<div><span>070 - 8832 - 9375</span></div>
				<div class="f_logo">BOOK Talk</div>
			</div>
			<!-- footer End -->
		</div>
		
<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
    </body>
</html>