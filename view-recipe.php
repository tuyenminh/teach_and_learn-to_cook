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
						<p>Cùng chúng tôi xem</p>
						<h1>Chi tiết công thức</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->
	<?php
		$pid = $_GET['pid'];
		// Truy vấn để lấy thông tin sản phẩm hiện tại và danh mục của nó
		$select_products = $conn->prepare("SELECT recipe.*, category.*, ingre.ingre_name AS ingre_ingre_name, unit.number AS unit_number, unit.unit_name AS unit_unit_name FROM `recipe` 
		INNER JOIN category ON category.id_cate=recipe.id_cate 
		INNER JOIN detail_ingre ON detail_ingre.recipe_id=recipe.id 
		INNER JOIN ingre ON detail_ingre.ingre_id=ingre.id 
		INNER JOIN unit ON detail_ingre.unit_id=unit.id 
		WHERE recipe.id = ?");
		$select_products->execute([$pid]);

		if ($select_products->rowCount() > 0) {
		$fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
		$productName = $fetch_products['name'];
		$categoryID = $fetch_products['id_cate'];

		// Truy vấn để lấy tất cả các sản phẩm cùng danh mục
		$related_products = $conn->prepare("SELECT * FROM `recipe` WHERE id_cate = :category AND id != :pid LIMIT 3");
		$related_products->bindValue(':category', $categoryID, PDO::PARAM_INT);
		$related_products->bindValue(':pid', $pid, PDO::PARAM_INT);
		$related_products->execute();

	?>
	<!-- single product -->
	<div class="single-product mt-150 mb-150" style= "margin-top: 50px;
													margin-bottom: 50px;">
		<div class="container">
			<div class="row" >
				<div class="col-md-8" style="border: 0.2px solid #ccc; 
											padding: 30px; 
											margin-bottom: 20px; 
											box-shadow: -4px 0 4px rgba(0, 0, 0, 0.1);
											max-width: 70.333333%;">
					<div class="row" >
						<div class="col-md-10">
							<div class="single-product-img">
								<h3><?= $fetch_products['name']; ?></h3>
								<p class="product-price"><span>Danh mục: <?= $fetch_products['name_cate']; ?></span></p>
								<p>Thời gian nấu: <?= $fetch_products['time']; ?></p>
								<ul class="product-share">
										<li><a href=""><i class="fab fa-facebook-f"></i></a></li>
										<li><a href=""><i class="fab fa-twitter"></i></a></li>
										<li><a href=""><i class="fab fa-google-plus-g"></i></a></li>
										<li><a href=""><i class="fab fa-linkedin"></i></a></li>
									</ul>
								<iframe width="100%" height="332" src="<?= $fetch_products['video']; ?>" frameborder="0" allowfullscreen></iframe>

							</div>
						</div>
						
					</div>
					<div class="row" >
						<div class="col-md-12">
							<p><?= $fetch_products['ingre']; ?></p>
						</div>
						<p style = "margin-left: 15px;"><strong>Nguyên liệu:</strong><p>
						<?php
							// Lấy giá trị recipe_id từ tham số URL
							$recipe_id = $_GET['pid'];

							// Truy vấn để lấy thông tin ingre_ingre_name, unit_unit_name, và unit_number
							$select_ingredients = $conn->prepare("SELECT ingre.ingre_name AS ingre_ingre_name, unit.unit_name AS unit_unit_name, unit.number AS unit_number
								FROM recipe
								INNER JOIN detail_ingre ON detail_ingre.recipe_id = recipe.id
								INNER JOIN ingre ON detail_ingre.ingre_id = ingre.id
								INNER JOIN unit ON detail_ingre.unit_id = unit.id
								WHERE recipe.id = ?");
							$select_ingredients->execute([$recipe_id]);

							if ($select_ingredients->rowCount() > 0) {
								while ($ingredient = $select_ingredients->fetch(PDO::FETCH_ASSOC)) {
									echo '<div class="col-md-12">';
									echo '<p style="margin-bottom: 0px; margin-top: 0px; ">' . $ingredient['unit_number'] . ' ' . $ingredient['unit_unit_name'] . ' ' . $ingredient['ingre_ingre_name'] . '</p><br>';
									echo '</div>';
								}
							} else {
								echo '<p>Không có thông tin nguyên liệu cho công thức này.</p>';
							}
						?>
						<div class="col-md-12">
							<p><?= $fetch_products['making']; ?></p>
						</div>

					</div>
				</div>
				<div class="col-md-4" style="border: 0.2px solid #ccc; 
											padding: 10px; 
											margin-bottom: 20px; 
											box-shadow: 4px 0 4px rgba(0, 0, 0, 0.1); 
											">
					<div class="frame">
    					<p>Tham khảo các khóa học</p>
					</div>
					<style>
						.frame {
						background-color: #F28123;
						padding: 10px; 
						border: 1px solid #ccc;
						text-align: center; 
						margin-bottom:20px;
					}

					.frame p {
						font-size: 20px;
						color: #fff; 
					}
					</style>
							<?php
								$select_random_products = $conn->prepare("SELECT * FROM `recipe` INNER JOIN category ON category.id_cate=recipe.id_cate ORDER BY RAND() LIMIT 10");
								$select_random_products->execute();

								if ($select_random_products->rowCount() > 0) {
									while ($fetch_random_products = $select_random_products->fetch(PDO::FETCH_ASSOC)) {
										?>
										<div class="row" style="padding: 10px;">
											<div class="col-md-6">
												<a href="view-courses.php?pid=<?= $fetch_random_products['id']; ?>"><img style="width: 150px; height:80px;" src="uploaded_img/<?= $fetch_random_products['image']; ?>" alt=""></a>
											</div>
											<div class="col-md-6">
												<a href="view-recipe.php?pid=<?= $fetch_random_products['id']; ?>">
													<p style="font-size: 15px;"><?= $fetch_random_products['name']; ?></p>
												</a>
												<p class="product-price"><span><?= $fetch_random_products['time']; ?></span></p>
											</div>
										</div>
										<?php
									}
								} else {
									echo "Không có dữ liệu";
								}
							?>

				</div>
			</div>
		</div>
	</div>
	<!-- end single product -->

	<!-- more products -->
	<div class="more-products mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">	
						<h3><span class="orange-text">Công thức</span> Liên quan</h3>
						<p>Những khóa học bổ ích, mới mẻ được cập nhật giúp học viên muốn học thêm liên quan đến khóa học ở trên.</p>
					</div>
				</div>
			</div>
			
			<div class="row">
				<?php
					while ($related_product = $related_products->fetch(PDO::FETCH_ASSOC)) {
				?>
				<div class="col-lg-4 col-md-6 text-center">
					<div class="single-product-item">
                        <div class="product-image">
                            <img src="uploaded_img/<?= $related_product['image']; ?>" alt="">
						</div>
                        <h3><?= $related_product['name']; ?></h3>
                        <p class="product-price"><span><?= $fetch_products['name_cate']; ?></span></p>
                        <a href="view-recipe.php?pid=<?= $related_product['id']; ?>" class="cart-btn">Xem chi tiết</a>
                    </div>
				</div>
				<?php
					}
					}else{
					echo '<p class="empty">Không có dữ liệu nào!</p>';
					}
				?>
			</div>
		</div>
	</div>
	<!-- end more products -->

	<?php include 'components/user_footer.php'; ?>

</body>
</html>