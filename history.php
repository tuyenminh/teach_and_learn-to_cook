<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'components/user_head.php'; ?>
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
						<?php include 'components/user_nav.php'; ?>
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
						<p>Cảm ơn bạn đã đồng hàng cùng CookingFood</p>
						<h1>Thông tin khóa học đăng kí</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- cart -->
	<div class="cart-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="cart-table-wrap">
						<table class="cart-table">
							<thead class="cart-table-head">
								<tr class="table-head-row" >
									<th class="product-name">Tên tài khoản</th>
                                    <th class="product-name">Email</th>
                                    <th class="product-name">Số điện thoại</th>
                                    <th class="product-name">Khóa học</th>
                                    <th class="product-name">Trạng thái</th>
                                    <th class="product-name">Ngày đăng kí</th>
									<th class="product-price">Tổng tiền</th>

								</tr>
							</thead>
							<tbody>
                            <?php
                                if($user_id == ''){
                                    echo '<p class="empty">Vui lòng đăng nhập để đăng kí</p>';
                                }else{
                                    $select_orders = $conn->prepare("SELECT * FROM `receipt` WHERE user_id = ?");
                                    $select_orders->execute([$user_id]);
                                    if($select_orders->rowCount() > 0){
                                        while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                            ?>
								<tr class="table-body-row">
									<form action="" method="post">
										
										<td class="product-name"><?= $fetch_orders['name']; ?></td>
										<td class="product-name"><?= $fetch_orders['email']; ?></td>
                                        <td class="product-name"><?= $fetch_orders['number']; ?></td>
										<td class="product-name"><?= $fetch_orders['total_course']; ?></td>
										<td class="product-name"><?= $fetch_orders['pay_status']; ?></td>
										<td class="product-name"><?= $fetch_orders['regis_date']; ?></td>
										<td class="product-price"><?= number_format($fetch_orders['total_price'], 0, ',', '.') . " VNĐ" ?></td>
									</form>
									
								</tr>
                                <?php
                                    }
                                    }else{
                                        echo '<p class="empty">Bạn chưa đăng kí khóa học!</p>';
                                    }
                                    }
                                ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include 'components/chatbox.php'; ?>

	<!-- end cart -->


	<!-- footer -->
	<?php include 'components/user_footer.php'; ?>

</body>
</html>