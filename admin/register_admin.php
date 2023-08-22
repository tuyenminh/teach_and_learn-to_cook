<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $email = $_POST['email'];
   $number = $_POST['number'];
   $address = $_POST['address'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);
   
   if($select_admin->rowCount() > 0){
      $message[] = 'Tài khoản đã tồn tại!';
   }else{
      if($pass != $cpass){
         $message[] = 'Xác nhận mật khẩu không khớp!';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, email, number, address, password) VALUES(?,?,?,?,?)");
         $insert_admin->execute([$name, $email, $number, $address, $cpass]);
         $message[] = 'Đã đăng kí thành công!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng kí tài</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- register admin section starts  -->
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
<?php include '../components/sidebar.php' ?>
<div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home">
						<use xlink:href="#stroked-home"></use>
					</svg></a></li>
			<li class="active" >Trang tài khoản quản trị</li>
		</ol>
	</div>
   <?php include '../components/message.php' ?>
   <section class="form-container">
      <form action="" method="POST">
         <h3>Đăng kí tài khoản</h3>
         <input type="text" name="name" maxlength="50" required placeholder="Nhập tên tài khoản" class="box" >
         <input type="text" name="email" maxlength="50" required placeholder="Nhập email" class="box">
         <input type="text" name="number" maxlength="20" required placeholder="Nhập số điện thoại" class="box">
         <input type="text" name="address" maxlength="20" required placeholder="Nhập địa chỉ" class="box">
         <input type="password" name="pass" maxlength="20" required placeholder="Nhập mật khẩu" class="box" >
         <input type="password" name="cpass" maxlength="20" required placeholder="Xác nhận mật khẩu" class="box">
         <input style = "width: 50rem;" type="submit" value="Đăng kí" name="submit" class="btn">
         
      </form>

   </section>
</div>
<!-- register admin section ends -->
















<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>