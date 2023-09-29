<?php
use Carbon\Carbon;
use Carbon\CarbonInterval;
include 'components/connect.php';
require('carbon/autoload.php');

$now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);

   // $address = $_POST['address'];
   // $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_course = $_POST['total_course'];
   $total_price = $_POST['total_price'];
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      // if($address == ''){
      //    $message[] = 'please add your address!';
      // }else{
         
         $insert_order = $conn->prepare("INSERT INTO `receipt`(user_id, name, number, method, total_course, total_price, email, regis_date) VALUES(?,?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $number, $method, $total_course, $total_price, $email, $now]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

		 echo '<script>alert("Thanh toán thành công");</script>';         
   }else{
		echo '<span>Giỏ hàng trống!</span>';
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
						<p>Khóa học bổ ích cho mọi người</p>
						<h1>Thanh toán</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- check out section -->
	<div class="checkout-section mt-150 mb-150">
		<div class="container">
		<form action="" method="post">
			<div class="row">
				<div class="col-lg-8">
					<div class="checkout-accordion-wrap">
						<div class="accordion" id="accordionExample">
						  <div class="card single-accordion">
						    <div class="card-header" id="headingOne">
						      <h5 class="mb-0">
						        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						          Thông tin khách hàng
						        </button>
						      </h5>
						    </div>

						    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
						      <div class="card-body">
						        <div class="billing-address-form">
                                
						        	
                                        <span style= "font-size: 15px;"><strong>
                                            Tên tài khoản
                                        </strong></span>
						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="text" name="name" value="<?= $fetch_profile['name'] ?>"placeholder="Tên học viên"></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Email
                                        </strong></span>

						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="email" name="email" value="<?= $fetch_profile['email'] ?>" placeholder="Email"></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Địa chỉ
                                        </strong></span>

						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="text" name="address" value="<?= $fetch_profile['address'] ?>" placeholder="Địa chỉ"></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Số điện thoại
                                        </strong></span>

						        		<p><input style= "width:100%; border: 1px solid #ccc; padding: 10px;" type="tel" name="number" value="<?= $fetch_profile['number'] ?>" placeholder="Số điện thoại"></p>
						        </div>
                                <div style ="padding-left:20px;">
                                    <a href="update_profile.php" class="boxed-btn">Cập nhật thông tin</a>
                                </div>
						      </div>
						    </div>
						  </div>
						  <div class="card single-accordion">
						    <div class="card-header" id="headingThree">
						      <h5 class="mb-0">
						        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						          Phương thức thanh toán
						        </button>
						      </h5>
						    </div>
						    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
						      <div class="card-body">
						        <div class="card-details">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<label class="input-group-text" for="inputGroupSelect01">Lựa chọn phương thức thanh toán</label>
										</div>

										<select class="custom-select" id="inputGroupSelect01" name="method" required>
											<option value="" disabled selected>Chọn...</option>
											<option value="Thẻ tín dụng">Thẻ tín dụng</option>
											<option value="Paytm">Paytm</option>
											<option value="Paypal">Paypal</option>
										</select>
									</div>
						        </div>
						      </div>
						    </div>
						  </div>
						</div>

					</div>
				</div>

				<div class="col-lg-4">
					<div class="order-details-wrap">
						<table class="order-details">
							<thead>
                           
								<tr>
									<th>Khóa học</th>
									<th>Giá</th>
								</tr>
                                
							</thead>
							<tbody class="checkout-details">
                            <?php
                                    $grand_total = 0;
                                    $cart_items[] = '';
                                    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                                    $select_cart->execute([$user_id]);
                                    if($select_cart->rowCount() > 0){
                                        while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                                        $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].') - ';
                                        $total_course = implode($cart_items);
                                        $grand_total += ($fetch_cart['price']);
                                ?>
								<input type="hidden" name="total_course" value="<?= $total_course; ?>">
								<input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
								<input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
								<input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
								<input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
								<!-- <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>"> -->

								<tr>
									<td style ="font-size: 15px;"><?= $fetch_cart['name']." "; ?></td>
									<td style ="font-size: 15px;"><?= number_format($fetch_cart['price']) . " VNĐ"; ?></td>
                                    
								</tr>
                                
								<?php
                                
                                        }
                                        
                                    }else{
                                        echo '<p style="color: red;" class="empty">Giỏ hàng trống!</p>';
                                    }
                                ?>
                                <tr class="total-data">
									<td><strong>Tổng tiền: </strong></td>
									<td><?php echo number_format($grand_total). " VNĐ"; ?></td>
								</tr>
							</tbody>
						</table>
                        <a href="giohang.php" class="boxed-btn">GIỎ HÀNG</a>			
					
    					<input style="font-family: 'Poppins', sans-serif;
									display: inline-block;
									background-color: #F28123;
									color: #fff;
									font-weight: normal;
									font-size: 1.5rem;
									padding: 10px 15px;" 
									class="boxed-btn" type="submit" value="Thanh toán" name="submit">
					</div>
				</div>
			</div>
		<form>
		</div>
	</div>
	<!-- end check out section -->

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