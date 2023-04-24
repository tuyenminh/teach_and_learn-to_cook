<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_payment'])){

   $order_id = $_POST['order_id'];
   $payment_status = $_POST['pay_status'];
   $update_status = $conn->prepare("UPDATE `receipt` SET pay_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'Tráng thái thanh toán đã cập nhật';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `receipt` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng kí</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- placed orders section starts  -->

<section class="placed-orders">

   <h1 class="heading">Phiếu đăng kí</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `receipt`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <!-- <p> user id : <span><?= $fetch_orders['user_id']; ?></span> </p> -->
      <p> Tên khách hàng : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Ngày đăng kí : <span><?= $fetch_orders['regis_date']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Số điện thoại : <span><?= $fetch_orders['number']; ?></span> </p>
      <!-- <p> address : <span><?= $fetch_orders['address']; ?></span> </p> -->
      <p> Tên khóa học : <span><?= $fetch_orders['total_course']; ?></span> </p>
      <p> Tổng tiền : <span><?= number_format($fetch_orders['total_price']). " VNĐ"; ?></span> </p>
      <p> Phương thức thanh toán : <span><?= $fetch_orders['method']; ?></span> </p>
      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="pay_status" class="drop-down">
            <option value="" selected disabled>Trạng thái--<?= $fetch_orders['pay_status'];?></option>
            <option value="Đang xử lý">Đang xử lý</option>
            <option value="Đã hoàn thành">Đã hoàn thành</option>
         </select>
         <div class="flex-btn">
            <input type="submit" value="Cập nhật" class="btn" name="update_payment">
            <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Xóa phiếu đăng kí?');">Xóa</a>
         </div>
      </form>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">Phiếu đăng kí trống!</p>';
   }
   ?>

   </div>

</section>

<!-- placed orders section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>