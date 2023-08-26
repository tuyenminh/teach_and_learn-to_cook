<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_user'])){

    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $password = sha1($_POST['password']);
   $password = filter_var($password, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);


   $update_users = $conn->prepare("UPDATE `users` SET name = ?, email = ?, number = ?, password = ?, address = ? WHERE id = ?");
   $update_users->execute([$name, $email, $number,  $password, $address]);

   $message[] = 'Cập nhật thành công!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cập nhật tài khoản</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- update product section starts  -->

<section class="update-product">

   <h1 class="heading">Cập nhật tài khoản</h1>

   <?php
      $update_id = $_GET['update'];
      $show_users = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
      $show_users->execute([$update_id]);
      if($show_users->rowCount() > 0){
         while($fetch_users = $show_users->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <span>Tên tài khoản</span>
      <input type="text" required placeholder="Nhập tên tài khoản" name="name" maxlength="100" class="box" value="<?= $fetch_users['name']; ?>">
      <span>Email</span>
      <input type="email" required placeholder="Nhập email" name="email" maxlength="100" class="box" value="<?= $fetch_users['email']; ?>">
      <span>Số điện thoại</span>
      <input type="text" required placeholder="Nhập số điện thoại" name="number" maxlength="100" class="box" value="<?= $fetch_users['number']; ?>">
      <span>Cập nhật mật khẩu</span>
      <input type="password" required placeholder="Nhập tên tài khoản" name="password" maxlength="100" class="box" value="<?= $fetch_users['password']; ?>">
      <span>Cập nhật địac chỉ</span>
      <input type="text" required placeholder="Nhập địa chỉ" name="address" maxlength="100" class="box" value="<?= $fetch_users['address']; ?>">
      <div class="flex-btn">
         <input type="submit" value="Cập nhật" class="btn" name="update_user">
         <a href="users_accounts.php" class="option-btn">Trở về</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">Khóa học trống!</p>';
      }
   ?>

</section>

<!-- update product section ends -->






<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-table.js"></script>
<script src="https://localhost/food_website_backend/admin/ckeditor/ckeditor.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->



</body>
</html>