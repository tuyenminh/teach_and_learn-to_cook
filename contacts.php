<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['message'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);
   $subject = $_POST['subject'];
   $subject = filter_var($subject, FILTER_SANITIZE_STRING);
   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ? AND subject = ?");
   $select_message->execute([$name, $email, $number, $msg, $subject]);

   if($select_message->rowCount() > 0){
    echo '<script>alert("Tin nhắn đã được gửi");</script>';   
}else{

      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message, subject) VALUES(?,?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg, $subject]);

      echo '<script>alert("Tin nhắn đã được gửi!");</script>';   

   }

}

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
							<a href="index.html">
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
										<a class="shopping-cart" href="giohang.php"><i class="fas fa-shopping-cart"></i><?php if ($user_id) { ?><span>(<?= $total_cart_items; ?>)</span><?php } ?></a>
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
							<h3>Search For:</h3>
							<input type="text" placeholder="Keywords">
							<button type="submit">Search <i class="fas fa-search"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end search arewa -->
	
	<!-- breadcrumb-section -->
	<div class="breadcrumb-section breadcrumb-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<p>Hỗ trợ 24/7</p>
						<h1>Liên hệ với chúng tôi</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- contact form -->
	<div class="contact-from-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 mb-5 mb-lg-0">
					<div class="form-title">
						<h2>Câu hỏi của bạn?</h2>
						<p>Hãy để lại lời nhắn khi có bất cứ câu hỏi nào liên quan đến thắc mắc về các khóa học. Chúng tôi sẽ trả lời ngay cho bạn thông qua email!</p>
					</div>
				 	<div id="form_status"></div>
					<div class="contact-form">
						<form type="POST" id="fruitkha-contact" action="" method="POST" onSubmit="return valid_datas( this );">
							<p>
								<input type="text" placeholder="Tên của bạn" name="name" id="name">
								<input type="email" placeholder="Email" name="email" id="email">
							</p>
							<p>
								<input type="tel" placeholder="Số điện thoại" name="number" id="phone">
								<input type="text" placeholder="Tiêu đề" name="subject" id="subject">
							</p>
							<p><textarea name="message" id="message" cols="30" rows="10" placeholder="Lời nhắn"></textarea></p>
							<input type="hidden" name="token" value="FsWga4&@f6aw" />
							<p><input type="submit" name="send" value="Gửi"></p>
						</form>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="contact-form-wrap">
						<div class="contact-form-box">
							<h4><i class="fas fa-map"></i> Địa chỉ CookingFood</h4>
							<p>34/8, Phường Phú Hưng <br> TP Bến Tre, Bến Tre. <br> Việt Nam</p>
						</div>
						<div class="contact-form-box">
							<h4><i class="far fa-clock"></i> Giờ hoạt động</h4>
							<p>Thứ 2 - Thứ 6: 8 sáng đến 17 giờ chiều  <br> Thứ 7- Chủ nhật: 10 sáng đến 16 chiều </p>
						</div>
						<div class="contact-form-box">
							<h4><i class="fas fa-address-book"></i> Liên hệ</h4>
							<p>Số điện thoại: +84 582268858 <br> Email: cookingfood@gmail.com</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end contact form -->

	<!-- find our location -->
	<div class="find-location blue-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<p> <i class="fas fa-map-marker-alt"></i> Vị trí CookingFood</p>
				</div>
			</div>
		</div>
	</div>
	<!-- end find our location -->

	<!-- google map section -->
	<div class="embed-responsive embed-responsive-21by9">
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26432.42324808999!2d-118.34398767954286!3d34.09378509738966!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2bf07045279bf%3A0xf67a9a6797bdfae4!2sHollywood%2C%20Los%20Angeles%2C%20CA%2C%20USA!5e0!3m2!1sen!2sbd!4v1576846473265!5m2!1sen!2sbd" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" class="embed-responsive-item"></iframe>
	</div>
	<!-- end google map section -->


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
					<p>Copyrights &copy; 2019 - <a href="https://imransdesign.com/">Imran Hossain</a>,  All Rights Reserved.<br>
						Distributed By - <a href="https://themewagon.com/">Themewagon</a>
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
	
</body>
</html>