<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_users'])){

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

   $select_users = $conn->prepare("SELECT * FROM `users` WHERE name = ? AND email = ?" );
   $select_users->execute([$name, $email]);

   if($select_users->rowCount() > 0){
      $message[] = 'Người dùng đã tồn tại!';
   }else{
      

         $insert_users = $conn->prepare("INSERT INTO `users`(name, email, number, password, address) VALUES(?,?,?,?,?)");
         $insert_users->execute([$name, $email, $number,$password, $address ]);

         $message[] = 'Thêm tài khoản mới thành công!';
      }

   }




if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `courses` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `courses` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tài khoản khách hàng</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- link boostrap -->
   <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
	<script type = "text/javascript" src= "ckeditor_4.21.0_full/ckeditor/ckeditor.js"></script>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin.css">
   <!-- <script src="js/jquery-1.11.1.min.js"></script>
   <script src="js/bootstrap.min.js"></script> -->



</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- add products section starts  -->
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
<section class="add-products">
   <?php include '../components/sidebar.php' ?>
   <div class="row">
		<ol class="breadcrumb">
			<li><a href="#"><svg class="glyph stroked home">
						<use xlink:href="#stroked-home"></use>
					</svg></a></li>
			<li class="active">Trang Tài khoản khách hàng</li>
		</ol>
	</div>
   <?php include '../components/message.php' ?>

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Thêm tài khoản khách hàng</h3>
      
      <input type="text" required placeholder="Nhập tên tài khoản" name="name" maxlength="100" class="box">
      <input type="email" required placeholder="Nhập email" name="email" maxlength="100" class="box">
      <input type="text" required placeholder="Nhập số điện thoại" name="number" maxlength="100" class="box">
      <input type="password" required placeholder="Nhập mật khẩu" name="password" maxlength="100" class="box">
      <input type="text" required placeholder="Nhập địa chỉ" name="address" maxlength="100" class="box">
      <input type="submit" value="Thêm tài khoản" name="add_users" class="btn">
   </form>
</section>
        </div>
        









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>
<!-- <script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-table.js"></script> -->
<!-- <script src="https://localhost/food_website_backend/admin/ckeditor/ckeditor.js"></script> -->


</body>
</html>