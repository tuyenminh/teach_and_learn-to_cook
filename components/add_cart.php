<?php
use Carbon\Carbon;
use Carbon\CarbonInterval;
require('carbon/autoload.php');

if (isset($_POST['add_to_cart'])) {
    if ($user_id == '') {
        header('location:login.php');
    } else {
        $pid = $_POST['pid'];
        $pid = filter_var($pid, FILTER_SANITIZE_STRING);
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

        $check_cart_item = $conn->prepare("SELECT * FROM `registration_form` WHERE regis_day = ? AND course_id = ? AND user_id = ? AND status = 'Chưa thanh toán'");
        $check_cart_item->execute([$now, $pid, $user_id]);

        if ($check_cart_item->rowCount() > 0) {
            echo '<script>alert("Sản phẩm đã có sẵn trong giỏ hàng!");</script>';
        } else {
            $insert_cart = $conn->prepare("INSERT INTO `registration_form` (regis_day, course_id, user_id, status) VALUES (?, ?, ?, 'Chưa thanh toán')");
            $insert_cart->execute([$now, $pid, $user_id]);
            echo '<script>alert("Thêm vào giỏ hàng thành công!");</script>';
        }
    }
}
?>
