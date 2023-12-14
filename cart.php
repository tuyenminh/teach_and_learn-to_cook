<?php
use Carbon\Carbon;
use Carbon\CarbonInterval;
require('carbon/autoload.php');
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};

if (isset($_POST['delete'])) {
	$cart_id = $_POST['cart_id'];
	$delete_cart_item = $conn->prepare("DELETE FROM `registration_form` WHERE id = ?");
	if ($delete_cart_item->execute([$cart_id])) {
		echo '<script>alert("Khóa học đã xóa!");</script>';
	} else {
		echo '<script>alert("Xóa khóa học thất bại!");</script>';
	}
 }
 


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
						<p>Khóa học bổ ích cho tất cả mọi người</p>
						<h1>Giỏ hàng</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- cart -->
	<div class="cart-section mt-150 mb-150">
		<div class="container">
			<div class = "row">
			<a href="history.php" style = "margin-bottom: 20px;" class="boxed-btn">Xem lịch sử đăng ký</a>			
		</div>
			<div class="row">
				<div class="col-lg-8 col-md-12">
					<div class="cart-table-wrap">
						<table class="cart-table">
							<thead class="cart-table-head">
								<tr class="table-head-row">
									<th class="product-remove"></th>
									<th class="product-image">Hình ảnh</th>
									<th class="product-name">Tên khóa học</th>
									<th class="product-price">Giá</th>
								</tr>
							</thead>
							<tbody>
                            <?php
$grand_total = 0;
$select_cart = $conn->prepare("SELECT courses.*, registration_form.id AS registration_id
    FROM registration_form
    INNER JOIN courses ON registration_form.course_id = courses.id
    WHERE registration_form.user_id = ? AND registration_form.status = 'Chưa thanh toán'");
$select_cart->execute([$user_id]);

if ($select_cart->rowCount() > 0) {
    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
        // Hiển thị thông tin khóa học từ bảng courses
        ?>
        <tr class="table-body-row">
            <form action="" method="post">
                <input type="hidden" name="cart_id" value="<?= $fetch_cart['registration_id']; ?>">
                <td class="product-remove">
                    <button style="border: none; background-color: rgba(0, 0, 0, 0);" type="submit" name="delete"
                            onclick="return confirm('Xóa khóa học?');"><a><i class="far fa-window-close"></i></a></button>
                </td>
                <td class="product-image"><img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt=""></td>
                <td class="product-name"><?= $fetch_cart['name']; ?></td>
                <td class="product-price"><?= $sub_total = number_format($fetch_cart['price']) . " VNĐ"; ?></td>
            </form>
        </tr>
        <?php
        $grand_total += str_replace([',', ' VNĐ'], '', $sub_total);
    }
} else {
    echo '<p class="empty">Giỏ hàng trống</p>';
}

                            ?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="col-lg-4">
					<div class="total-section">
						<table class="total-table">
							<thead class="total-table-head">
								<tr class="table-total-row">
									<th>Tổng tiền</th>
									<th>Giá</th>
								</tr>
							</thead>
							<tbody>
                                
								<tr class="total-data">
									<td><strong>Tổng tiền: </strong></td>
									<td><?php echo number_format($grand_total). " VNĐ"; ?></td>
								</tr>
								
							</tbody>
						</table>
						<div class="cart-buttons">
						<?php if ($select_cart->rowCount() > 0): ?>
							<a href="checkout.php" class="boxed-btn black">Thanh toán</a>
						<?php else: ?>
							<a href="#" class="boxed-btn black" disabled>Thanh toán</a>
						<?php endif; ?>
					</div>

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