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