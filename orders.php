<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Mua khóa học</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>Khóa học của bạn</h3>
   <p><a href="html.php">Trang chủ</a> <span> / Khóa học của bạn</span></p>
</div>

<section class="orders">

   <h1 class="title">Khóa học của bạn</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">Vui lòng đăng nhập để đăng kí</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `receipt` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Ngày mua: <span><?= $fetch_orders['regis_date']; ?></span></p>
      <p>Tên tài khoản: <span><?= $fetch_orders['name']; ?></span></p>
      <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
      <p>Số điện thoại: <span><?= $fetch_orders['number']; ?></span></p>
      <p>Phương thức thanh toán: <span><?= $fetch_orders['method']; ?></span></p>
      <p>Tổng số khóa học: <span><?= $fetch_orders['total_course']; ?></span></p>
      <p>Tổng tiền: <span><?= number_format($fetch_orders['total_price'], 0, ',', '.') . " VNĐ" ?>/-</span></p>
      <p>Trạng thái thanh toán: <span style="color:<?php if($fetch_orders['pay_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['pay_status']; ?></span> </p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">Chưa có khóa học nào được mua!</p>';
      }
      }
   ?>

   </div>

</section>










<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->






<!-- custom js file link  -->
<script src="home-/js/script.js"></script>

</body>
</html>