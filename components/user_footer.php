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

	<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>


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
<!-- -----------------------------------recipe.php------------------------------------------------- -->
<script>
   // Bắt sự kiện click trên các mục danh mục
   $('.product-filters li').click(function () {
      $('.product-filters li').removeClass('active');
      $(this).addClass('active');
      var categoryId = $(this).data('category');
      
      // Điều chỉnh truy vấn SQL để lấy tên danh mục từ bảng 'category'
      var sqlQuery = 'SELECT recipe.*, category.name_cate FROM recipe';

      if (categoryId !== '*') {
         sqlQuery += ' INNER JOIN category ON category.id_cate = recipe.id_cate WHERE recipe.id_cate = ' + categoryId;
      }

      sqlQuery += ' LIMIT 9';

      // Gửi yêu cầu AJAX để lấy sản phẩm theo danh mục
      $.ajax({
         type: 'POST',
         url: 'get_recipes.php',
         data: { sql: sqlQuery },
         success: function (response) {
               $('#product-list').html(response);
         }
      });
   });

   // Bắt sự kiện click khi người dùng nhấn "Tất cả"
   $('.product-filters li[data-filter="*"]').click(function () {
      // Điều chỉnh truy vấn SQL để lấy tất cả sản phẩm
      var sqlQuery = 'SELECT * FROM `recipe` INNER JOIN category ON category.id_cate=recipe.id_cate WHERE category.id_cate = recipe.id_cate ';

      sqlQuery += ' LIMIT 9';

      // Gửi yêu cầu AJAX để lấy tất cả sản phẩm
      $.ajax({
         type: 'POST',
         url: 'get_recipes.php',
         data: { sql: sqlQuery },
         success: function (response) {
               $('#product-list').html(response);
         }
      });
   });

   </script>

<script>
				// Trang hiện tại (được truyền từ mã JavaScript)
$page = isset($_POST['page']) ? $_POST['page'] : 1;

// Số sản phẩm trên mỗi trang
$itemsPerPage = 9;

// Tính vị trí bắt đầu của sản phẩm trên trang hiện tại
$start = ($page - 1) * $itemsPerPage;

// Truy vấn SQL để lấy sản phẩm cho trang hiện tại
$sql = "SELECT courses.*, category.name_cate FROM courses INNER JOIN category ON category.id_cate = courses.id_cate LIMIT $start, $itemsPerPage";
// Hàm để tải sản phẩm theo trang
function loadProductsByPage(page) {
    $.ajax({
        type: 'POST',
        url: 'get_products.php',
        data: { sql: sqlQuery, page: page }, // Truyền trang hiện tại vào yêu cầu AJAX
        success: function (response) {
            $('#product-list').html(response);
        }
    });
}

// Bắt sự kiện click trên các nút phân trang
$('.pagination-wrap ul li a').click(function (e) {
    e.preventDefault(); // Ngăn chặn hành động mặc định của thẻ a

    // Lấy số trang từ thuộc tính href của thẻ a
    var page = $(this).attr('href');

    // Xử lý số trang để lấy số
    page = parseInt(page.replace(/\D/g, ''));

    // Gọi hàm để tải sản phẩm theo trang
    loadProductsByPage(page);
});

			</script>