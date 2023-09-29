
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
            $html .= '<form action="" method="post">';
            $html .= '    <input type="hidden" name="pid" value="' . $row['id'] . '">';
            $html .= '    <input type="hidden" name="name" value="' . $row['name'] . '">';
            $html .= '    <input type="hidden" name="image" value="' . $row['image'] . '">';
            $html .= '    <input type="hidden" name="price" value="' . $row['price'] . '">';

            $html .= '    <div class="product-image">';
            $html .= '        <a href="view-courses.php?pid=' . $row['id'] . '"><img src="uploaded_img/' . $row['image'] . '" alt=""></a>';
            $html .= '    </div>';
            $html .= '    <h3>' . $row['name'] . '</h3>';
            $html .= '    <p class="product-price"><span>' . $row['name_cate'] . '</span>' . number_format($row['price'], 0, ',', '.') . ' VNĐ</p>';

            $html .= '    <button style="border: none; background-color: rgba(0, 0, 0, 0);" type="submit" name="add_to_cart"><a class="cart-btn"><i class="fas fa-shopping-cart"></i></a></button>';
            $html .= '</form>';
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