
<div class="row product-lists" >
 
<?php
// Kết nối CSDL
require_once("components/connect.php"); // Thay thế bằng kết nối cơ sở dữ liệu của bạn
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$itemsPerPage = 9;

// Tính vị trí bắt đầu của sản phẩm trên trang hiện tại
$start = ($page - 1) * $itemsPerPage;

// Truy vấn SQL để lấy sản phẩm cho trang hiện tại và số sản phẩm trên mỗi trang
$sql = "SELECT * FROM courses LIMIT $start, $itemsPerPage";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Xử lý dữ liệu và tạo nội dung HTML cho sản phẩm
$html = '';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<div class="col-lg-4 col-md-6 text-center courses">';
    $html .= '<div class="single-product-item">';
    $html .= '<div class="product-image">';
    $html .= '<a href="view-courses.php?pid=' . $row['id'] . '"><img src="uploaded_img/' . $row['image'] . '" alt=""></a>';
    $html .= '</div>';
    $html .= '<h3>' . $row['name'] . '</h3>';
    $html .= '<p class="product-price"><span>' . $row['name_cate'] . '</span>' . number_format($row['price'], 0, ',', '.') . ' VNĐ</p>';
    $html .= '<a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng</a>';
    $html .= '</div>';
    $html .= '</div>';
}

// Trả về nội dung HTML cho sản phẩm
echo $html;
?>
</div>
