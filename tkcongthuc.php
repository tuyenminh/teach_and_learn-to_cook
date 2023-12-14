<?php

include 'components/connect.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

include 'components/add_cart.php';

// Số sản phẩm hiển thị trên mỗi trang
$items_per_page = 9;

// Trang hiện tại
$page = isset($_GET['page']) ? $_GET['page'] : 1;

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
    <!-- end search arewa -->

    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <h1>Kết quả tìm kiếm</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->
    <div class="product-section mt-150">
        <div class="container">
            <div class="row product-lists">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $search_box = $_POST['keyword'];
                    $select_products = $conn->prepare("SELECT recipe.*, category.name_cate FROM recipe LEFT JOIN category ON category.id_cate = recipe.id_cate WHERE recipe.name LIKE :search_box");
                    $select_products->bindValue(':search_box', '%' . $search_box . '%', PDO::PARAM_STR);
                    $select_products->execute();

                    if ($select_products->rowCount() > 0) {
                        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
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
                    } else {
                        echo '<p class="empty">Không có khóa học nào!</p>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php
$search_query = $conn->prepare("SELECT COUNT(*) FROM recipe LEFT JOIN category ON category.id_cate = recipe.id_cate WHERE recipe.name LIKE :search_box");
$search_query->bindValue(':search_box', '%' . $search_box . '%', PDO::PARAM_STR);
$search_query->execute();
$total_products = $search_query->fetchColumn();

// Tính toán số trang dựa trên tổng số sản phẩm
$total_pages = ceil($total_products / $items_per_page);

?>

    <!-- Hiển thị số trang -->
    <div class="row">
    <div class="col-lg-12 text-center" style = "margin-bottom: 20px;">
        <div class="pagination-wrap">
            <ul>
                <?php
                // Hiển thị nút "Trước" và "Tiếp" cho phân trang
                if ($page > 1) {
                    echo '<li><a href="tkcongthuc.php?page=' . ($page - 1) . '">Trước</a></li>';
                }
                
                // Kiểm tra xem $total_pages đã được định nghĩa chưa trước khi sử dụng
                if (isset($total_pages)) {
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $activeClass = ($page == $i) ? 'active' : '';
                        echo '<li class="' . $activeClass . '"><a href="tkcongthuc.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                }

                if ($page < $total_pages) {
                    echo '<li><a href="tkcongthuc.php?page=' . ($page + 1) . '">Tiếp</a></li>';
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

    <!-- footer -->
    <?php include 'components/user_footer.php'; ?>

</body>
</html>
