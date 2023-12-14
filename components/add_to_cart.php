<?php
use Carbon\Carbon;
use Carbon\CarbonInterval;
require('carbon/autoload.php');

if (isset($_GET['action']) && $_GET['action'] == 'buy_again' && isset($_GET['pid'])) {
    $pid = $_GET['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

    // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng và có trạng thái "Đã thanh toán" chưa
    $check_cart_item = $conn->prepare("SELECT * FROM `registration_form` WHERE regis_day = ? AND course_id = ? AND user_id = ? AND status = 'Đã thanh toán'");
    $check_cart_item->execute([$now, $pid, $user_id]);

    if ($check_cart_item->rowCount() > 0) {
        // Sản phẩm có sẵn và đã thanh toán, thêm vào giỏ hàng
        $insert_cart = $conn->prepare("INSERT INTO `registration_form` (regis_day, course_id, user_id, status) VALUES (?, ?, ?, 'Chưa thanh toán')");
        $insert_cart->execute([$now, $pid, $user_id]);
        echo '<script>alert("Thêm vào giỏ hàng thành công!");</script>';
    } else {
        echo '<script>alert("Sản phẩm không tồn tại hoặc đã thanh toán!");</script>';
    }
} else {
    // Xử lý logic khác nếu cần
}
?>
