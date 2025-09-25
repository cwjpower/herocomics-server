<?php
/*
 * Desc : 梨??뺣낫 , html : book_information.html
 * 	FB : https://developers.facebook.com/docs/plugins/share-button/#example
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-term.php';

if ( empty($_GET['id']) ) {
	lps_js_back( '?꾩꽌 ?꾩씠?붽? 議댁옱?섏? ?딆뒿?덈떎.' );
}

$book_id = $_GET['id'];
$book_rows = lps_get_book($book_id);

$fb_url = MOBILE_URL . '/book/book.php?id=' . $book_id;	// facebook ?대낫?닿린??
$is_pkg = $book_rows['is_pkg'];
$book_title = $book_rows['book_title'];
$author = $book_rows['author'];
$publisher = $book_rows['publisher'];
$isbn = $book_rows['isbn'];
$normal_price = $book_rows['normal_price'];
$sale_price = $book_rows['sale_price'];
$upload_type = $book_rows['upload_type'];
$period_from = $book_rows['period_from'];
$period_to = $book_rows['period_to'];
$book_status = $book_rows['book_status'];
$user_id = $book_rows['user_id'];
$created_dt = $book_rows['created_dt'];
$discount_rate = $normal_price ? number_format(100 - ($sale_price / $normal_price * 100)) . '% ?좎씤 +' : '';

$epub_path = $book_rows['epub_path'];
$epub_name = $book_rows['epub_name'];
$cover_img = $book_rows['cover_img'];

$category_first = $book_rows['category_first'];
$category_second = $book_rows['category_second'];
$category_third = $book_rows['category_third'];

$book_meta = lps_get_book_meta($book_id);

$introduction_book = empty($book_meta['lps_introduction_book']) ? '' : $book_meta['lps_introduction_book'];
$introduction_author = empty($book_meta['lps_introduction_author']) ? '' : $book_meta['lps_introduction_author'];
$publisher_review = empty($book_meta['lps_publisher_review']) ? '' : $book_meta['lps_publisher_review'];
$book_table = empty($book_meta['lps_book_table']) ? '' : $book_meta['lps_book_table'];
$lps_res_accept_new = empty($book_meta['lps_res_accept_new']) ? '': unserialize($book_meta['lps_res_accept_new']);
if ($lps_res_accept_new) {
	$book_new_accept_date = date('Y-m-d H:i:s', $lps_res_accept_new['accept_dt']);
} else {
	$book_new_accept_date = '';
}

if ( $book_status != '3000' ) {
	lps_js_back( '二꾩넚?⑸땲?? ?꾩옱 ?ъ슜?????녿뒗 梨낆엯?덈떎.' );
}

// ?④퍡 援щℓ??梨?$books_cart = lps_get_books_by_cart( $book_id );

// require_once MOBILE_PATH . '/mobile-header.php';

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="google-signin-client_id" content="1082609763302-i7mdug0np5l874jkd0h3k1tuj67tr7e7.apps.googleusercontent.com">
		
		<meta property="og:url"           content="<?php echo $fb_url ?>" />
		<meta property="og:type"          content="website" />
		<meta property="og:title"         content="BOOKTALK.WORLD" />
		<meta property="og:description"   content="<?php echo $book_title ?>" />
		<meta property="og:image"         content="<?php echo $cover_img ?>" />
		
		<title>BOOK Talk</title>
		<link rel="shortcut icon" href="favicon.ico">
		<!-- css -->
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/jquery.mobile-1.4.5.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/globals.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/myinfo.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/mypage.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/main.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/board.css">
		<!-- js -->
		<script src="<?php echo INC_URL ?>/js/jquery.min.js"></script>
		<script src="<?php echo INC_URL ?>/js/jquery.mobile-1.4.5.min.js"></script>
	</head>
	<body>
	

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content book-info-area">
				<input type="hidden" id="book_id" value="<?php echo $book_id ?>">
				<h2 class="hide">梨??뺣낫</h2>
				<div class="book-box">
					<div class="book-view-top">
						<h3 class="book-view-tit"><?php echo $book_title ?></h3>
						<ul class="book-view-list">
							<li>?λⅤ : <?php echo wps_get_term_name($category_third) ?></li>
							<li>???: <?php echo $author ?></li>
							<!-- li>??릿??: ?띻만??/li> -->
							<li>異쒗뙋??: <?php echo $publisher ?></li>
							<!-- li>異쒓컙??: 20161210</li> -->
							<li>e-ISBN : <?php echo $isbn ?></li>
						</ul>
					</div>
					<div class="book-view-cont">
						<div class="book-view-img">
							<div style="float: right;">
								<!-- div class="fb-share-button" data-href="<?php echo $fb_url ?>" data-layout="button"></div> -->
								<a href="#" id="facebook-share"><img src="<?php echo MOBILE_URL ?>/img/common/icon_sns_facebook.png" style="height: 25px;"></a>
							</div>
							<div class="book-img-box">
								<div class="book-opc"></div>
								<img class="book-img" src="<?php echo $cover_img ?>" alt="梨??쒖?">
							</div>
						</div>
						<ul class="btn-view-cont">
							<li><a href="#" class="btn-skyblue">誘몃━ 蹂닿린</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/book/book_activity.php?id=<?php echo $book_id ?>" data-ajax="false" class="btn-green">?대꼈??/a></li>
							<li><a href="#" class="btn-pink" id="btn-new-wishlist">李쒗븯湲?/a></li>
						</ul>
					</div>
				</div>		
				<div class="book-box">
					<ul class="plrtb10 price-area">
						<li>?뺢? <?php echo number_format($normal_price) ?>??/li>
						<li class="price-point">?먮ℓ媛 <span>(<?php echo $discount_rate ?> ??? point ?곷┰)</span></li>
						<li class="price">
							<div class="price-dc"><?php echo number_format($sale_price) ?>??/div>
							<div class="btn-buy-r">
								<a href="#" id="btn-item-buy" class="btn-info-buy">諛붾줈 援щℓ</a>
								<a href="#myLibraryCart" id="btn-item-cart" data-rel="popup" class="btn-info-cart">?λ컮援щ땲 ?닿린</a>
							</div>
						</li>
					</ul>
					
					<!-- ?λ컮援щ땲 popup -->
					<div data-role="popup" id="myLibraryCart" class="ui-content" data-overlay-theme="b">
						<div class="popup-area">
							<div class="popup-tit">?λ컮援щ땲</div>
							<p class="popup-txt">?λ컮援щ땲???닿꼈?듬땲??<br><br>吏湲?諛붾줈 ?λ컮援щ땲濡?br>?대룞?섏떆寃좎뒿?덇퉴?</p>
							<ul class="btn2-popup">
								<li><a href="../cart/cart.php" class="btn-skyblue" data-ajax="false">?뺤씤</a></li>
								<li><a href="#" data-rel="back" class="btn-gray">痍⑥냼</a></li>
							</ul>
						</div>
					</div>
					<!-- ?λ컮援щ땲 popup End -->
		
				</div>
				<div>
					<!-- div class="book-info-conts">
						<h3>?곸젣?뺣낫</h3>
						<div class="book-info-cont">
							<div class="book-info-txt">?롫뵲?댄몴 ?ㅼ씠?대━?뤿뒗 ?⑹뵫?섍퀬 ?좎풄??留ㅻ젰?쇰줈 留롮? ?대뱾?먭쾶 湲띿젙???먮꼫吏瑜??꾪븯怨??덈뒗 ?멸린 釉붾줈嫄? ?섎떎?뚯쪖戮뺚숈씠 湲고쉷???ㅼ씠?대━遺곸씠?? 洹몃??먭쾶???ㅻТ ???뚮???15??媛? 1?꾩뿉 ??沅뚯뵫 袁몄????⑤궡?ㅺ컙 ?ㅼ씠?대━媛 ?덈떎. ?좊Ц?먯꽌 ?ㅽ겕?⑺븳 湲곗궗瑜?遺숈씠嫄곕굹 梨낆쓣 ?쎈떎 </div>
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">?붾낫湲?/button>
					</div> -->
					<div class="book-info-conts">
						<h3>梨??뚭컻</h3>
						<div class="book-info-cont">
							<div class="book-info-txt"><?php echo $introduction_book ?></div>
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">?붾낫湲?/button>
					</div>
					<div class="book-info-conts">
						<h3>????뚭컻</h3>
						<div class="book-info-cont">
							<div class="book-info-txt"><?php echo $introduction_author ?></div>
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">?붾낫湲?/button>
					</div>
					<div class="book-info-conts">
						<h3>異쒗뙋???쒗룊</h3>
						<div class="book-info-cont">
							<div class="book-info-txt"><?php echo $publisher_review ?></div>
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">?붾낫湲?/button>
					</div>
		<?php 
		if (!wps_get_current_user_id()) {
		?>
					<div class="book-info-conts">
						<h3>??梨낆쓣 ??移쒓뎄</h3>
						<div class="book-info-cont">
							<div class="book-info-txt">(濡쒓렇?몄씠 ?꾩슂???쒕퉬?ㅼ엯?덈떎.)</div>
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">?붾낫湲?/button>
					</div>
		<?php 
		} else {
		?>
					<div class="book-info-conts">
						<h3>??梨낆쓣 ??移쒓뎄</h3>
						<div class="book-info-cont">
							<div class="book-info-txt">
								<h4>?⑺씗 ?곌컻?뚮Ц 媛뺢컧李? * 誘멸뎄??/h4>
							</div> 
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">?붾낫湲?/button>
					</div>
		<?php 
		}
		?>
					<div class="book-info-conts">
						<h3>而ㅻ??덊떚 ??궧</h3>
						<div class="book-info-cont">
							<div class="book-info-txt">
								<h4>?먯닔 - 1,322??br>
								?쒖쐞 - ?꾩껜 15??/ ?λⅤ 6??* 誘멸뎄??/h4>
							</div> 
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">?붾낫湲?/button>
					</div>
					<div class="book-info-conts">
						<h3>?④퍡 援щℓ??梨?/h3>
						<div class="buy-together-box">
							<ul class="buy-together-book">
					<?php 
					if (!empty($books_cart)) {
						foreach ($books_cart as $key => $val) {
							$wb_id = $val['book_id'];
							$withb = lps_get_book($wb_id);
							
							$wb_title = $withb['book_title'];
							$wb_cover = $withb['cover_img'];
							
					?>
								<li>
									<div class="book-img-pt">
										<div class="book-img-box">
											<div class="book-opc"></div>
											<a href="book.php?id=<?php echo $wb_id ?>" data-ajax="false"><img class="book-img" src="<?php echo $wb_cover ?>" alt="?쒖?"></a>
										</div>
										<div class="book-name ellipsis"><a href="book.php?id=<?php echo $wb_id ?>" data-ajax="false"><?php echo $wb_title ?></a></div>
									</div>
								</li>
					<?php 
						}
					}
					?>
							</ul>
						</div>
						<button class="btn-txt-more2 btn-more-down">?붾낫湲?/button>
					</div>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			$(".btn-txt-more").click(function(){
				$(this).toggleClass("btn-more-up");
				$(this).text(function(i, v) {
					if (v == "?붾낫湲?) {
						$(this).parent().find(".book-info-txt").css("height", "auto");
						return "?묎린";
					} else {
						$(this).parent().find(".book-info-txt").css("height", "54px");
						return "?붾낫湲?;
					}
// 				   return v === '?붾낫湲? ? '?묎린' : '?붾낫湲?
				});
			});
			
			$(".btn-txt-more2").click(function(){
				$(this).toggleClass("btn-more-up");
				$(this).text(function(i, v) {
					if (v == "?붾낫湲?) {
						$(this).parent().find(".buy-together-box").css("height", "auto");
						return "?묎린";
					} else {
						$(this).parent().find(".buy-together-box").css("height", "141px");
						return "?붾낫湲?;
					}
				});
			});

			$("#btn-new-wishlist").click(function(e) {
				e.preventDefault();
				$.ajax({
					type : "POST",
					url : "./ajax/book-new-wishlist.php",
					data : {
						"bookID" : $("#book_id").val()
					},
					dataType : "json",
					success : function(res) {
						if (res.code != "0") {
							alert(res.msg);
						} else {
							if (res.result === true) {
// 								alert("李쒕━?ㅽ듃???깅줉?덉뒿?덈떎.");
								if (confirm("李쒕━?ㅽ듃???깅줉?덉뒿?덈떎.\n李쒕━?ㅽ듃濡??대룞?섏떆寃좎뒿?덇퉴?")) {
									location.href = "../mypage/wishlist.php";
								}
							} else {
// 								alert("?대? 李쒕━?ㅽ듃???깅줉??梨낆엯?덈떎.");
								if (confirm("?대? 李쒕━?ㅽ듃???깅줉??梨낆엯?덈떎.\n李쒕━?ㅽ듃濡??대룞?섏떆寃좎뒿?덇퉴?")) {
									location.href = "../mypage/wishlist.php";
								}
							}
						}
					}
				});
			});

			// 諛붾줈 援щℓ?섍린
			$("#btn-item-buy").click(function(e) {
				e.preventDefault();

				$.ajax({
					type : "POST",
					url : "../cart/ajax/add-buy-item.php",
					data : {
						"bookID[]" : $("#book_id").val()
					},
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "../cart/pay.php";
						} else {
							alert(res.msg);
						}
					}
				});
				
			});

			// ?λ컮援щ땲 ?닿린
			$("#btn-item-cart").click(function(e) {
				e.preventDefault();

				$.ajax({
					type : "POST",
					url : "../cart/ajax/cart-new-item.php",
					data : {
						"bookID[]" : $("#book_id").val()
					},
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							$("#myLibraryCart").popup("open");
						} else {
							alert(res.msg);
						}
					}
				});
				
			});

// 			// facebook button click
			$("#facebook-share").click(function() {
				shareFB();
			});

		}); // $

		window.fbAsyncInit = function () {
		    FB.init({
		        appId	: "<?php echo $wps_fb_app_id ?>",
		        xfbml	: true,
		        version	: "v2.8"
		    });
		};

		(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/ko_KR/sdk.js";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

		function shareFB() {
			FB.ui({
	            method: 'share',
	            display: 'popup',
	            href: '<?php echo $fb_url ?>',
	            caption: 'BOOKTalk.WORLD',
	        }, function (response) {
	            if (response === null) {
// 	                console.log('post was not shared');
	            } else {
// 	                console.log('post was shared');

	                $.ajax({
						type : "POST",
						url : "./ajax/share-sns.php",
						data : {
							"bookID" : $("#book_id").val()
						},
						dataType : "json",
						success : function(res) {
							if (res.code != "0") {
								alert(res.msg);
							} else {
							}
						}
					});
	            }
	        });
		}
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>