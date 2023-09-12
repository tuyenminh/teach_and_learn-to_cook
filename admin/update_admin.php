<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};


   if(isset($_POST['update_admin'])){

   $uid = $_POST['uid'];
   $uid = filter_var($uid, FILTER_SANITIZE_STRING);

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


   $update_users = $conn->prepare("UPDATE `admin` SET name = ?, email = ?, number = ?, password = ?, address = ? WHERE id = ?");
   $update_users->execute([$name, $email, $number,  $password, $address, $uid]);

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
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
<?php include '../components/sidebar.php' ?>
<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home">
						<use xlink:href="#stroked-home"></use>
					</svg></a></li>
			<li class="active">Trang tài khoản quản trị</li>
		</ol>
	</div>
   <?php include '../components/message.php' ?>

<section class="update-product">

   <h1 class="heading">Cập nhật tài khoản</h1>

   <?php
      $update_id = $_GET['update_admin'];
      $show_users = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $show_users->execute([$update_id]);
      if($show_users->rowCount() > 0){
         while($fetch_accounts = $show_users->fetch(PDO::FETCH_ASSOC)){  
   ?>

   <form action="" method="POST" enctype="multipart/form-data">
   <input type="hidden" name="uid" value="<?= $fetch_accounts['id']; ?>">
      <span>Tên tài khoản</span>
      <input type="text" required placeholder="Nhập tên tài khoản" name="name" maxlength="100" class="box" value="<?= $fetch_accounts['name']; ?>">
      <span>Email</span>
      <input type="email" required placeholder="Nhập email" name="email" maxlength="100" class="box" value="<?= $fetch_accounts['email']; ?>">
      <span>Số điện thoại</span>
      <input type="text" required placeholder="Nhập số điện thoại" name="number" maxlength="100" class="box" value="<?= $fetch_accounts['number']; ?>">
      <span>Cập nhật mật khẩu</span>
      <input type="password" required placeholder="Nhập mật khẩu" name="password" maxlength="100" class="box">
      <span>Cập nhật địa chỉ</span>
      <input type="text" required placeholder="Nhập địa chỉ" name="address" maxlength="100" class="box" value="<?= $fetch_accounts['address']; ?>">
      <div class="flex-btn">
         <input type="submit" value="Cập nhật" class="btn" name="update_admin">
         <a href="list_admin.php" class="option-btn">Trở về</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">Tài khoản trống!</p>';
      }
   ?>

</section>
   </div>
<!-- update product section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>
<script src="js/bootstrap-table.js"></script>
<script src="https://localhost/food_website_backend/admin/ckeditor/ckeditor.js"></script>



</body>
</html>