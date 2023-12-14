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
	<?php
		include 'timkiemcongthuc.php';
	?>
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
									<h1>Món ăn gia đình</h1>								<div class="hero-btns">
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
									<a href="shop.html" class="boxed-btn">Xem công thức</a>
									<a href="contact.html" class="bordered-btn">Liên hệ</a>
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
						<h3><span class="orange-text">Danh mục</span>Công thức</h3>
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
                    $activeClass = (isset($category_id) && $category_id == $fetch_categories['id_cate']) ? 'active' : '';
                    echo '<li class="' . $activeClass . '" data-category="' . $fetch_categories['id_cate'] . '">' . $fetch_categories['name_cate'] . '</li>';
                }
                ?>
						</ul>
                    </div>
                </div>
            </div>
			<div class="row product-lists">
				<?php
        // Số sản phẩm hiển thị trên mỗi trang
        $items_per_page = 9;

        // Trang hiện tại
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // Điều chỉnh truy vấn SQL để lấy tên danh mục từ bảng 'category'
        $sqlQuery = 'SELECT recipe.*, category.name_cate FROM recipe LEFT JOIN category ON category.id_cate = recipe.id_cate';

        // Điều kiện kiểm tra nếu người dùng chọn một danh mục cụ thể
        if (isset($_GET['category_id']) && is_numeric($_GET['category_id']) && $_GET['category_id'] != 0) {
            $category_id = $_GET['category_id'];
            $sqlQuery .= ' WHERE recipe.id_cate = ' . $category_id;
        }

        // Sửa truy vấn SQL để lấy sản phẩm của trang hiện tại
        $startIndex = ($page - 1) * $items_per_page;
        $sqlQuery .= ' LIMIT ' . $startIndex . ', ' . $items_per_page;

        $select_products = $conn->prepare($sqlQuery);
        $select_products->execute();
								while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
                  ?>
                           <div class="col-lg-4 col-md-6 text-center courses">
                              <div class="single-product-item">
                                    <div class="product-image">
                                       <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">                                    </div>
                                       <h3><?= $fetch_products['name']; ?></h3>
                                       <p class="product-price"><span><?= $fetch_products['name_cate']; ?></span></p>
                                       <a href="view-recipe.php?pid=<?= $fetch_products['id']; ?>" class="cart-btn">Xem chi tiết</a>
                              </div>
                           </div>
                  <?php
                        }
                  ?>
            </div>
         </div>
		 <?php
// Truy vấn SQL để lấy tổng số sản phẩm trong danh mục đã chọn hoặc tất cả sản phẩm
$total_products_query = $conn->query("SELECT count(*) FROM `recipe`" . (isset($category_id) && $category_id != 0 ? " WHERE id_cate = $category_id" : ""));
$total_products = $total_products_query->fetchColumn();

// Tính toán số trang dựa trên tổng số sản phẩm
$total_pages = ceil($total_products / $items_per_page);
?>
<!-- Hiển thị số trang -->
<div class="row">
    <div class="col-lg-12 text-center">
        <div class="pagination-wrap">
            <ul>
                <?php
                // Hiển thị nút "Trước" và "Tiếp" cho phân trang
                if ($page > 1) {
                    echo '<li><a href="recipe.php?page=' . ($page - 1) . (isset($category_id) ? '&category_id=' . $category_id : '') . '">Trước</a></li>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    $activeClass = ($page == $i) ? 'active' : '';
                    echo '<li class="' . $activeClass . '"><a href="recipe.php?page=' . $i . (isset($category_id) ? '&category_id=' . $category_id : '') . '">' . $i . '</a></li>';
                }
                if ($page < $total_pages) {
                    echo '<li><a href="recipe.php?page=' . ($page + 1) . (isset($category_id) ? '&category_id=' . $category_id : '') . '">Tiếp</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<style>
    .pagination-wrap li.active a {
        background-color: #F28123;
        color: #fff;
    }
	.product-filters li.active,
	.product-filters li:hover {
        background-color: #F28123;
        color: #fff;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Bắt sự kiện click trên các mục danh mục
    $('.product-filters li').click(function () {
        $('.product-filters li').removeClass('active');
        $(this).addClass('active');
        var categoryId = $(this).data('category');
        
        // Redirect trang về trang 1 khi chuyển danh mục
        window.location.href = 'recipe.php?page=1&category_id=' + categoryId;
    });
    // Bắt sự kiện click khi người dùng nhấn "Tất cả"
    $('.product-filters li[data-filter="*"]').click(function () {
        // Đặt `categoryId` là 0 (hoặc giá trị mặc định của bạn)
        var categoryId = 0;
        window.location.href = 'recipe.php?page=1&category_id=' + categoryId;
    });

	$(document).ready(function () {
    // Lấy giá trị category_id từ URL
    var urlParams = new URLSearchParams(window.location.search);
    var categoryId = urlParams.get('category_id');
    
    // Nếu không có category_id hoặc là '0' (Tất cả), thì tô màu cho 'Tất cả'
    if (!categoryId || categoryId === '0') {
        $('.product-filters li[data-filter="*"]').addClass('active');
    } else {
        // Nếu có category_id khác '0', tô màu cho danh mục có category_id tương ứng
        $('.product-filters li[data-category]').removeClass('active'); // Xóa tất cả lớp 'active' trước đó
        $('.product-filters li[data-category="' + categoryId + '"]').addClass('active');
    }
});

// Bắt sự kiện click trên các mục danh mục
$('.product-filters li').click(function () {
    $('.product-filters li').removeClass('active');
    $(this).addClass('active');
    var categoryId = $(this).data('category');
    
    // Redirect trang về trang 1 khi chuyển danh mục
    window.location.href = 'recipe.php?page=1&category_id=' + categoryId;
});
</script>
			<!-- <div class="row">
				<div class="col-lg-12 text-center">
					<div class="pagination-wrap">
						<ul>
							<li><a href="#">Trước</a></li>
							<li><a href="#">1</a></li>
							<li><a class="active" href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">Tiếp</a></li>
						</ul>
					</div>
				</div>
			</div> -->
			
			<?php include 'components/chatbox.php'; ?>

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

	<!-- footer -->
	<?php include 'components/user_footer.php'; ?>


</body>
</html>