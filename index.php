<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Responsive Bootstrap4 Shop Template, Created by Imran Hossain from https://imransdesign.com/">

	<!-- title -->
	<title>CookingFood</title>

	<!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/img/favicon.png">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<!-- fontawesome -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/all.min.css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/bootstrap/css/bootstrap.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/owl.carousel.css">
	<!-- magnific popup -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/magnific-popup.css">
	<!-- animate css -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/animate.css">
	<!-- mean menu css -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/meanmenu.min.css">
	<!-- main style -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/main.css">
	<!-- responsive -->
	<link rel="stylesheet" href="fruitkha-1.0.0/fruitkha-1.0.0/assets/css/responsive.css">

</head>
<body>
	
	<!--PreLoader-->
    <div class="loader">
        <div class="loader-inner">
            <div class="circle"></div>
        </div>
    </div>
    <!--PreLoader Ends-->
	
	<!-- header -->
	<div class="top-header-area" id="sticker">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-sm-12 text-center">
					<div class="main-menu-wrap">
						<!-- logo -->
						<div class="site-logo">
							<a href="index.php">
								<img style="width: 100%;" src="fruitkha-1.0.0/fruitkha-1.0.0/assets/img/logo.png" alt="">
							</a>
						</div>
						<!-- logo -->

						<!-- menu start -->
						<nav class="main-menu">
							<ul>
								<li class="current-list-item"><a href="index.php">Trang chủ</a>
								</li>
								<li><a href="recipe.php">Công thức nấu ăn</a></li>
								<li><a href="news.php">Tin tức</a></li>
								<li><a href="contacts.php">Liên hệ</a></li>
								<li>
									<div class="header-icons">
									<?php
										$count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
										$count_cart_items->execute([$user_id]);
										$total_cart_items = $count_cart_items->rowCount();
									?>
									<style>
										.shopping-cart {
											position: relative; 
											text-decoration: none; 
										}
											.shopping-cart span {
											position: absolute; 
											top: -10px; 
											right: -10px; 
											background-color: #F28123; 
											color: white; 
											border-radius: 50%; 
											padding: 5px 10px; 
											font-size: 14px; 
											}
									</style>
										<a class="shopping-cart" href="cart.php"><i class="fas fa-shopping-cart"></i><?php if ($user_id) { ?><span>(<?= $total_cart_items; ?>)</span><?php } ?></a>
										<a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>
										<?php
										$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
										$select_profile->execute([$user_id]);
										if ($select_profile->rowCount() > 0) {
											$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

											?>
												<a href="profile.php" class="btn">
												<?php
													$name = $fetch_profile['name'];
													$spacePosition = strpos($name, ' ');

													if ($spacePosition !== false) {
														// Tên có dấu cách, hiển thị họ
														$lastName = substr(strrchr($name, ' '), 1);
														echo '<a href="profile.php" class="btn">' . $lastName . '</a>';
													} else {
														// Tên không có dấu cách, hiển thị icon user
														echo '<a href="profile.php" class="btn"><i class="fas fa-user"></i></a>';
													}
													?>

												</a>
												<a style = "font-size: 15px; "href="components/user_logout.php" onclick="return confirm('Đăng xuất khỏi trang web này?');" class="delete-btn">| Đăng xuất</a>
											<?php
										} else {
											?>
											<a style="font-size: 15px;" href="login.php">Đăng nhập</a>
											<a style="font-size: 15px;" href="register.php">| Đăng ký</a>
											<?php
										}
										?>
											
									</div>
								</li>
							</ul>
						</nav>
						<a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
						<div class="mobile-menu"></div>
						<!-- menu end -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end header -->
	
	<!-- search area -->
	<div class="search-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<span class="close-btn"><i class="fas fa-window-close"></i></span>
					<div class="search-bar">
						<div class="search-bar-tablecell">
							<h3>Tìm kiếm:</h3>
							<input type="text" placeholder="Từ khóa">
							<button type="submit">Tìm kiếm <i class="fas fa-search"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end search area -->

	<!-- home page slider -->
	<div class="homepage-slider">
		<!-- single home slider -->
		<div class="single-homepage-slider homepage-bg-1">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-lg-7 offset-lg-1 offset-xl-0">
						<div class="hero-text">
							<div class="hero-text-tablecell">
								<p class="subtitle">Dạy và học hiệu quả</p>
								<h1>Món ăn gia đình</h1>
								<div class="hero-btns">
									<a href="shop.html" class="boxed-btn">Đăng kí học</a>
									<a href="contact.html" class="bordered-btn">Liên hệ</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- single home slider -->
		<div class="single-homepage-slider homepage-bg-2">
			<div class="container">
				<div class="row">
					<div class="col-lg-10 offset-lg-1 text-center">
						<div class="hero-text">
							<div class="hero-text-tablecell">
							<p class="subtitle">Mỗi buổi học là niềm vui</p>
								<h1>Đăng kí học ngay hôm nay</h1>
								<div class="hero-btns">
									<a href="shop.html" class="boxed-btn">Xem khóa học</a>
									<a href="contact.html" class="bordered-btn">Liên hệ</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- single home slider -->
		<div class="single-homepage-slider homepage-bg-3">
			<div class="container">
				<div class="row">
					<div class="col-lg-10 offset-lg-1 text-right">
						<div class="hero-text">
							<div class="hero-text-tablecell">
							<p class="subtitle">Dễ hiểu, dễ thực hiện</p>
								<h1>Công thức nấu ăn đa dạng</h1>
								<div class="hero-btns">
									<a href="recipe.php" class="boxed-btn">Xem công thức</a>
									<a href="contacts.php" class="bordered-btn">Liên hệ</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end home page slider -->

	<!-- features list section -->
	<div class="list-section pt-80 pb-80">
		<div class="container">

			<div class="row">
				<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
					<div class="list-box d-flex align-items-center">
						<div class="list-icon">
							<i class="fas fa-shipping-fast"></i>
						</div>
						<div class="content">
							<h3>Cấp chứng chỉ </h3>
							<p>Sau khi hoàn thành khóa học</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
					<div class="list-box d-flex align-items-center">
						<div class="list-icon">
							<i class="fas fa-phone-volume"></i>
						</div>
						<div class="content">
							<h3>Hỗ trợ 24/7</h3>
							<p>Tất cả các ngày trong tuần</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="list-box d-flex justify-content-start align-items-center">
						<div class="list-icon">
							<i class="fas fa-sync"></i>
						</div>
						<div class="content">
							<h3>Xem công thức</h3>
							<p>Dễ hiểu, dễ thực hiện</p>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<!-- end features list section -->

	<!-- product section -->
	<div class="product-section mt-150 mb-150">
		<div class="container">
		<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">	
						<h3><span class="orange-text">Danh mục</span> Khóa học</h3>
					</div>
				</div>
			</div>
			
			<div class="row">
                <div class="col-md-12">
                    <div class="product-filters">
						<ul>
							<li class="active" data-filter="*">Tất cả</li>
							<?php
							$select_categories = $conn->query("SELECT * FROM category");
							while ($fetch_categories = $select_categories->fetch(PDO::FETCH_ASSOC)) {
								echo '<li data-category="' . $fetch_categories['id_cate'] . '">' . $fetch_categories['name_cate'] . '</li>';
							}
							?>
						</ul>
                    </div>
                </div>
            </div>

			<div id="product-list">
				<div class="row product-lists">
						<?php
							$select_products = $conn->prepare("SELECT * FROM `courses` INNER JOIN category ON category.id_cate=courses.id_cate WHERE category.id_cate = courses.id_cate LIMIT 9");
							$select_products->execute();
								while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
						?>
									<div class="col-lg-4 col-md-6 text-center courses">
										<div class="single-product-item">
											<form action="" method="post">
												<input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
												<input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
												<input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
												<input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
											<div class="product-image">
													<a href="view-courses.php?pid=<?= $fetch_products['id']; ?>"><img src="uploaded_img/<?= $fetch_products['image']; ?>" alt=""></a>
												</div>
												<h3><?= $fetch_products['name']; ?></h3>
												<p class="product-price"><span><?= $fetch_products['name_cate']; ?></span><?= number_format($fetch_products['price'], 0, ',', '.') . " VNĐ" ?></p>
												
												<button style ="border: none;   background-color: rgba(0, 0, 0, 0); 
												" type="submit" name="add_to_cart"><a class="cart-btn"><i class="fas fa-shopping-cart"></i></a></button>

											</form>
												
										</div>
									</div>
						<?php
								}
						?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="pagination-wrap">
						<ul>
							<li><a href="#">Prev</a></li>
							<li><a class="active" href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">Next</a></li>
						</ul>
					</div>
				</div>
			</div>
			<script>
			// Định nghĩa các biến quan trọng
var currentPage = 1; // Trang hiện tại
var totalPages = <?php echo $totalPages; ?>; // Tổng số trang

// Xử lý khi người dùng nhấn vào nút "Prev"
$('#prev-page').click(function (e) {
    e.preventDefault();
    if (currentPage > 1) {
        currentPage--;
        loadProductsByPage(currentPage);
    }
});

// Xử lý khi người dùng nhấn vào nút "Next"
$('#next-page').click(function (e) {
    e.preventDefault();
    if (currentPage < totalPages) {
        currentPage++;
        loadProductsByPage(currentPage);
    }
});

// Xử lý khi người dùng nhấn vào một số trang cụ thể
$('.page-link').click(function (e) {
    e.preventDefault();
    var targetPage = parseInt($(this).data('page'));
    if (!isNaN(targetPage) && targetPage >= 1 && targetPage <= totalPages) {
        currentPage = targetPage;
        loadProductsByPage(currentPage);
    }
});

// Hàm để tải sản phẩm dựa trên số trang
function loadProductsByPage(page) {
    // Gửi yêu cầu AJAX để lấy sản phẩm cho trang được chỉ định
    var sqlQuery = 'SELECT courses.*, category.name_cate FROM courses LIMIT ' + (page - 1) * <?php echo $itemsPerPage; ?> + <?php echo $itemsPerPage; ?>;
    $.ajax({
        type: 'POST',
        url: 'get_pages.php',
        data: { sql: sqlQuery },
        success: function (response) {
            $('#product-list').html(response);
        }
    });
}

			</script>
		</div>
	</div>
	<!-- end products -->
	
	<!-- advertisement section -->
	<div class="abt-section mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<div class="abt-bg">
						<a href="https://www.youtube.com/watch?v=EKem2tqU-ic" class="video-play-btn popup-youtube"><i class="fas fa-play"></i></a>
					</div>
				</div>
				<div class="col-lg-6 col-md-12">
					<div class="abt-text">
						<p class="top-sub">Phát triển từ năm 2022</p>
						<h2>Học nấu ăn cùng <span class="orange-text">CookingFood</span></h2>
						<p>Một khóa học nấu ăn thú vị và bổ ích dành cho những người muốn học cách nấu ăn từ cơ bản đến nâng cao. Khóa học này được thiết kế để giúp bạn phát triển kỹ năng nấu ăn, từ việc chuẩn bị các nguyên liệu đơn giản cho các món ăn hằng ngày cho đến việc tạo ra các món ăn phức tạp và thú vị.</p>
						<p>Trong khóa học CookingFood, bạn sẽ được hướng dẫn bởi các đầu bếp chuyên nghiệp và các chuyên gia về ẩm thực. Bạn sẽ học cách lựa chọn nguyên liệu tốt nhất, cách thực hiện các kỹ thuật nấu ăn cơ bản và nâng cao, cách tổ chức và trình bày món ăn một cách esthetically, cũng như cách tạo ra các món ăn ngon và độc đáo từ nhiều nền ẩm thực khác nhau.</p>
						<p>Khóa học CookingFood không chỉ giúp bạn trở thành một đầu bếp tài năng mà còn giúp bạn thư giãn và thúc đẩy sự sáng tạo trong nấu ăn. Bất kể bạn là người mới bắt đầu hay đã có kinh nghiệm, khóa học này sẽ cung cấp cho bạn kiến thức và kỹ năng cần thiết để tự tin nấu ăn và tạo ra những bữa ăn ngon miệng cho gia đình và bạn bè.</p>
						<a href="about.html" class="boxed-btn mt-4">Tìm hiểu thêm</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end advertisement section -->
	
	<!-- shop banner -->
	<section class="shop-banner">
    	<div class="container">
        	<h3>Công thức nấu ăn<br> đa dạng <span class="orange-text">đang chờ bạn khám phá...</span></h3>
            <!-- <div class="sale-percent"><span>Sale! <br> Upto</span>50% <span>off</span></div> -->
            <a href="recipe.php" class="cart-btn btn-lg">Xem ngay</a>
        </div>
    </section>
	<!-- end shop banner -->

	<!-- latest news -->
	<!-- <div class="latest-news pt-150 pb-150">
		<div class="container">

			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">	
						<h3><span class="orange-text">Tin tức</span> Mới nhất</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, fuga quas itaque eveniet beatae optio.</p>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4 col-md-6">
					<div class="single-latest-news">
						<a href="single-news.html"><div class="latest-news-bg news-bg-1"></div></a>
						<div class="news-text-box">
							<h3><a href="single-news.html">You will vainly look for fruit on it in autumn.</a></h3>
							<p class="blog-meta">
								<span class="author"><i class="fas fa-user"></i> Admin</span>
								<span class="date"><i class="fas fa-calendar"></i> 27 December, 2019</span>
							</p>
							<p class="excerpt">Vivamus lacus enim, pulvinar vel nulla sed, scelerisque rhoncus nisi. Praesent vitae mattis nunc, egestas viverra eros.</p>
							<a href="single-news.html" class="read-more-btn">read more <i class="fas fa-angle-right"></i></a>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="single-latest-news">
						<a href="single-news.html"><div class="latest-news-bg news-bg-2"></div></a>
						<div class="news-text-box">
							<h3><a href="single-news.html">A man's worth has its season, like tomato.</a></h3>
							<p class="blog-meta">
								<span class="author"><i class="fas fa-user"></i> Admin</span>
								<span class="date"><i class="fas fa-calendar"></i> 27 December, 2019</span>
							</p>
							<p class="excerpt">Vivamus lacus enim, pulvinar vel nulla sed, scelerisque rhoncus nisi. Praesent vitae mattis nunc, egestas viverra eros.</p>
							<a href="single-news.html" class="read-more-btn">read more <i class="fas fa-angle-right"></i></a>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 offset-md-3 offset-lg-0">
					<div class="single-latest-news">
						<a href="single-news.html"><div class="latest-news-bg news-bg-3"></div></a>
						<div class="news-text-box">
							<h3><a href="single-news.html">Good thoughts bear good fresh juicy fruit.</a></h3>
							<p class="blog-meta">
								<span class="author"><i class="fas fa-user"></i> Admin</span>
								<span class="date"><i class="fas fa-calendar"></i> 27 December, 2019</span>
							</p>
							<p class="excerpt">Vivamus lacus enim, pulvinar vel nulla sed, scelerisque rhoncus nisi. Praesent vitae mattis nunc, egestas viverra eros.</p>
							<a href="single-news.html" class="read-more-btn">read more <i class="fas fa-angle-right"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 text-center">
					<a href="news.html" class="boxed-btn">More News</a>
				</div>
			</div>
		</div>
	</div> -->
	<!-- end latest news -->

	<!-- footer -->
	<div class="footer-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="footer-box about-widget">
						<h2 class="widget-title">Thông tin</h2>
						<p>Tổng đài tư vấn: 1800 6148 hoặc 1800 2027 08h00 - 20h00 (Miễn phí cước gọi)</p>
							<p>Góp ý phản ánh: 028 7109 9232</p>
							<p>Liên hệ Quản Lý Học Viên: 028 7300 2672</p>
							<p>08h00 - 20h00</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box get-in-touch">
						<h2 class="widget-title">Thời gian hoạt động</h2>
						<ul>
							<li>34/8, Phường Phú Hưng, Tp Bến Tre, Tỉnh Bến Tre</li>
							<li>cookingfood@gmail.com</li>
							<li>+84 582268858</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box pages">
						<h2 class="widget-title">Trang chính</h2>
						<ul>
							<li><a href="index.php">Trang chủ</a></li>
							<li><a href="recipe.php">Công thức</a></li>
							<li><a href="news.php">Tin tức</a></li>
							<li><a href="contacts.php">Liên hệ</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box subscribe">
						<h2 class="widget-title">Đăng kí</h2>
						<p>Đăng kí để nhận thông tin các khóa học mới nhất.</p>
						<form action="index.html">
							<input type="email" placeholder="Email">
							<button type="submit"><i class="fas fa-paper-plane"></i></button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end footer -->
	
	<!-- copyright -->
	<div class="copyright">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<p>Copyrights &copy; 2019 - <a href="https://imransdesign.com/">CookingFood</a>,  All Rights Reserved.<br>
					</p>
				</div>
				<div class="col-lg-6 text-right col-md-12">
					<div class="social-icons">
						<ul>
							<li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-linkedin"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-dribbble"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end copyright -->
	
	<!-- jquery -->
	<script src="fruitkha-1.0.0/fruitkha-1.0.0/assets/js/jquery-1.11.3.min.js"></script>
	<!-- bootstrap -->
	<script src="fruitkha-1.0.0/fruitkha-1.0.0/assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- count down -->
	<script src="fruitkha-1.0.0/fruitkha-1.0.0/assets/js/jquery.countdown.js"></script>
	<!-- isotope -->
	<script src="fruitkha-1.0.0/fruitkha-1.0.0/assets/js/jquery.isotope-3.0.6.min.js"></script>
	<!-- waypoints -->
	<script src="fruitkha-1.0.0/fruitkha-1.0.0/assets/js/waypoints.js"></script>
	<!-- owl carousel -->
	<script src="fruitkha-1.0.0/fruitkha-1.0.0/assets/js/owl.carousel.min.js"></script>
	<!-- magnific popup -->
	<script src="fruitkha-1.0.0/fruitkha-1.0.0/assets/js/jquery.magnific-popup.min.js"></script>
	<!-- mean menu -->
	<script src="fruitkha-1.0.0/fruitkha-1.0.0/assets/js/jquery.meanmenu.min.js"></script>
	<!-- sticker js -->
	<script src="fruitkha-1.0.0/fruitkha-1.0.0/assets/js/sticker.js"></script>
	<!-- main js -->
	<script src="fruitkha-1.0.0/fruitkha-1.0.0/assets/js/main.js"></script>
	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<script>
	// Bắt sự kiện click trên các mục danh mục
	$('.product-filters li').click(function () {
		$('.product-filters li').removeClass('active');
		$(this).addClass('active');
		var categoryId = $(this).data('category');
		
		// Điều chỉnh truy vấn SQL để lấy tên danh mục từ bảng 'category'
		var sqlQuery = 'SELECT courses.*, category.name_cate FROM courses';

		if (categoryId !== '*') {
			sqlQuery += ' INNER JOIN category ON category.id_cate = courses.id_cate WHERE courses.id_cate = ' + categoryId;
		}

		sqlQuery += ' LIMIT 9';

		// Gửi yêu cầu AJAX để lấy sản phẩm theo danh mục
		$.ajax({
			type: 'POST',
			url: 'get_products.php',
			data: { sql: sqlQuery },
			success: function (response) {
				$('#product-list').html(response);
			}
		});
	});

	// Bắt sự kiện click khi người dùng nhấn "Tất cả"
	$('.product-filters li[data-filter="*"]').click(function () {
		// Điều chỉnh truy vấn SQL để lấy tất cả sản phẩm
		var sqlQuery = 'SELECT * FROM `courses` INNER JOIN category ON category.id_cate=courses.id_cate WHERE category.id_cate = courses.id_cate ';

		sqlQuery += ' LIMIT 9';

		// Gửi yêu cầu AJAX để lấy tất cả sản phẩm
		$.ajax({
			type: 'POST',
			url: 'get_products.php',
			data: { sql: sqlQuery },
			success: function (response) {
				$('#product-list').html(response);
			}
		});
	});
	</script>

</body>
</html>