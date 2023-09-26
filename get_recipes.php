
<div class="row product-lists" >
 
<?php
// Kết nối CSDL
require_once("components/connect.php"); // Thay thế bằng kết nối cơ sở dữ liệu của bạn

// Lấy truy vấn SQL từ yêu cầu AJAX
if (isset($_POST['sql'])) {
    $sql = $_POST['sql'];

    try {
        // Thực hiện truy vấn SQL
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Xây dựng danh sách sản phẩm trong định dạng HTML
        $html = '';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $html .= '<div class="col-lg-4 col-md-6 text-center courses">';
            $html .= '<div class="single-product-item">';
            $html .= '<div class="product-image">';
            $html .= '<img src="uploaded_img/' . $row['image'] . '" alt="">';
            $html .= '</div>';
            $html .= '<h3>' . $row['name'] . '</h3>';
            $html .= '<p class="product-price"><span>' . $row['name_cate'] . '</span>' . '</p>';
            $html .= '<a href="view-recipe.php?pid=' . $row['id'] . '" class="cart-btn">Xem chi tiết</a>';
            $html .= '</div>';
            $html .= '</div>';
        }

        // Trả về danh sách sản phẩm trong định dạng HTML
        echo $html;
    } catch (PDOException $e) {
        echo "Lỗi truy vấn CSDL: " . $e->getMessage();
    }
} else {
    echo "Không có truy vấn SQL được gửi.";
}

// Đóng kết nối CSDL
$conn = null;
?>
</div>