<?php
use Carbon\Carbon;
use Carbon\CarbonInterval;
include 'components/connect.php';
require('carbon/autoload.php');

$now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);

   // $address = $_POST['address'];
   // $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_course = $_POST['total_course'];
   $total_price = $_POST['total_price'];
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      // if($address == ''){
      //    $message[] = 'please add your address!';
      // }else{
         
         $insert_order = $conn->prepare("INSERT INTO `receipt`(user_id, name, number, method, total_course, total_price, email, regis_date) VALUES(?,?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $number, $method, $total_course, $total_price, $email, $now]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

         echo '<script>alert("Đăng kí khóa học thành công");</script>';   
         // }
      
   }else{
      echo '<script>alert("Gior hàng trống");</script>';   
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thanh toán</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="home-/css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>Thanh toán</h3>
   <p><a href="home.php">Trang chủ</a> <span> /Thanh toán</span></p>
</div>

<section class="checkout">

   <h1 class="title">Phiếu đăng kí</h1>

<form action="" method="post">

   <div class="cart-items">
      <h3>Khóa học</h3>
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].') - ';
               $total_course = implode($cart_items);
               $grand_total += ($fetch_cart['price']);
      ?>
      <p><span class="name"><?= $fetch_cart['name'].": "; ?><?= number_format($fetch_cart['price']) . " VNĐ"; ?></span></p>
      <?php
            }
         }else{
            echo '<p class="empty">Giỏ hàng trống!</p>';
         }
      ?>
      <p class="grand-total"><span class="name">Tổng tiền :</span><span class="price"><?= number_format($grand_total). " VNĐ"; ?></span></p>
      <a href="cart.php" class="btn">Xem giỏ hàng</a>
   </div>

   <input type="hidden" name="total_course" value="<?= $total_course; ?>">
   <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
   <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
   <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
   <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
   <!-- <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>"> -->

   <div class="user-info">
      <h3>Thông tin khách hàng</h3>
      <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
      <!-- <p><i class="fas fa-phone"></i><span><?= $fetch_profile['regis_date'] ?></span></p> -->
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
      <a href="update_profile.php" class="btn">Cập nhật hồ sơ</a>
      <!-- <h3>delivery address</h3>
      <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['address'] == ''){echo 'please enter your address';}else{echo $fetch_profile['address'];} ?></span></p>
      <a href="update_address.php" class="btn">update address</a> -->
      <select name="method" class="box" required>
         <option value="" disabled selected>Lựa chọn phương thức thanh toán --</option>
         <!-- <option value="cash on delivery">cash on delivery</option> -->
         <option value="Thẻ tín dụng">Thẻ tín dụng</option>
         <option value="Paytm">Paytm</option>
         <option value="Paypal">Paypal</option>
      </select>
      <!-- <input type="submit" value="place order" class="btn <?php if($fetch_profile['address'] == ''){echo 'disabled';} ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit"> -->
      <input type="submit" value="Thanh toán" class="btn " style="width:100%; background:var(--red); color:var(--white);" name="submit">

   </div>


</form>
   
</section>









<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->






<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>