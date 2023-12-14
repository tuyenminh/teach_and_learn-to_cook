<?php
include 'components/connect.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="product-list">
        <div class="row product-lists">
            <!-- Sản phẩm sẽ được hiển thị ở đây -->
        </div>
    </div>

    <button class="load-more-button">Xem thêm</button>

    <script>
        var currentPage = 1;

        // Hiển thị sản phẩm ban đầu
        loadProducts(currentPage);

        // Bắt sự kiện click trên nút "Xem thêm"
        $('.load-more-button').click(function () {
            currentPage++;
            loadProducts(currentPage);
        });

        function loadProducts(page) {
            // Điều chỉnh truy vấn SQL để lấy sản phẩm theo trang
            var sqlQuery = 'SELECT courses.*, category.name_cate FROM courses ' +
                'INNER JOIN category ON category.id_cate = courses.id_cate ' +
                'LIMIT ' + (page - 1) * 9 + ', 9';

            // Gửi yêu cầu AJAX để lấy sản phẩm
            $.ajax({
                type: 'POST',
                url: 'get_products.php',
                data: { sql: sqlQuery },
                success: function (response) {
                    // Nếu có dữ liệu, thêm vào danh sách sản phẩm
                    if (response.trim() !== "") {
                        $('#product-list .product-lists').append(response);
                    } else {
                        // Nếu không có dữ liệu, ẩn nút "Xem thêm"
                        $('.load-more-button').hide();
                    }
                }
            });
        }
    </script>
</body>
</html>

