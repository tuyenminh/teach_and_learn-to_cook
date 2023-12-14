<?php
include 'components/connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Các khai báo khác -->
</head>
<body>

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

<div id="product-list">
    <div class="row product-lists">
        <?php
        // Số sản phẩm hiển thị trên mỗi trang
        $items_per_page = 9;

        // Trang hiện tại
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // Điều chỉnh truy vấn SQL để lấy tên danh mục từ bảng 'category'
        $sqlQuery = 'SELECT courses.*, category.name_cate FROM courses LEFT JOIN category ON category.id_cate = courses.id_cate';

        // Điều kiện kiểm tra nếu người dùng chọn một danh mục cụ thể
        if (isset($_GET['category_id']) && is_numeric($_GET['category_id']) && $_GET['category_id'] != 0) {
            $category_id = $_GET['category_id'];
            $sqlQuery .= ' WHERE courses.id_cate = ' . $category_id;
        }

        // Sửa truy vấn SQL để lấy sản phẩm của trang hiện tại
        $startIndex = ($page - 1) * $items_per_page;
        $sqlQuery .= ' LIMIT ' . $startIndex . ', ' . $items_per_page;

        $select_products = $conn->prepare($sqlQuery);
        $select_products->execute();

        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="col-lg-4 col-md-6 text-center courses">
                <div class="single-product-item">
                    <form action="" method="post">
                        <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                        <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                        <!-- <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>"> -->
                        <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                        <!-- <div class="product-image">
                            <a href="view-courses.php?pid=<?= $fetch_products['id']; ?>"><img src="uploaded_img/<?= $fetch_products['image']; ?>" alt=""></a>
                        </div> -->
                        <h3><?= $fetch_products['name']; ?></h3>
                        <p class="product-price"><span><?= $fetch_products['name_cate']; ?></span><?= number_format($fetch_products['price'], 0, ',', '.') . " VNĐ" ?></p>
                        <button style="border: none; background-color: rgba(0, 0, 0, 0);" type="submit" name="add_to_cart"><a class="cart-btn"><i class="fas fa-shopping-cart"></i></a></button>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<?php
// Truy vấn SQL để lấy tổng số sản phẩm trong danh mục đã chọn hoặc tất cả sản phẩm
$total_products_query = $conn->query("SELECT count(*) FROM `courses`" . (isset($category_id) && $category_id != 0 ? " WHERE id_cate = $category_id" : ""));
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
                    echo '<li><a href="home.php?page=' . ($page - 1) . (isset($category_id) ? '&category_id=' . $category_id : '') . '">Trước</a></li>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    $activeClass = ($page == $i) ? 'active' : '';
                    echo '<li class="' . $activeClass . '"><a href="home.php?page=' . $i . (isset($category_id) ? '&category_id=' . $category_id : '') . '">' . $i . '</a></li>';
                }
                if ($page < $total_pages) {
                    echo '<li><a href="home.php?page=' . ($page + 1) . (isset($category_id) ? '&category_id=' . $category_id : '') . '">Tiếp</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<style>
    .pagination-wrap li.active a {
        background-color: #007bff;
        color: #fff;
    }
    .product-filters li.active {
        background-color: #007bff;
        color: #fff;
    }
    .product-filters li.active,
.product-filters li:hover {
    background-color: #007bff;
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
        window.location.href = 'home.php?page=1&category_id=' + categoryId;
    });
    // Bắt sự kiện click khi người dùng nhấn "Tất cả"
    $('.product-filters li[data-filter="*"]').click(function () {
        // Đặt `categoryId` là 0 (hoặc giá trị mặc định của bạn)
        var categoryId = 0;
        window.location.href = 'home.php?page=1&category_id=' + categoryId;
    });
$(document).ready(function () {
    // Lấy giá trị category_id từ URL
    var urlParams = new URLSearchParams(window.location.search);
    var categoryId = urlParams.get('category_id');
    
    // Nếu không có category_id hoặc là '0' (Tất cả), thì tô màu cho 'Tất cả'
    if (!categoryId || categoryId === '0') {
        $('.product-filters li[data-category="0"]').addClass('active');
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
    window.location.href = 'home.php?page=1&category_id=' + categoryId;
});



</script>

</body>
</html>
