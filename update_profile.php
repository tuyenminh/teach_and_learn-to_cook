<?php

include 'components/connect.php';

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

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);

   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

  
   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $user_id]);
      echo '<script>alert("Cập nhật tên tài khoản thành công!");</script>';  
   }

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_email->execute([$email]);
      if($select_email->rowCount() > 0){
        echo '<script>alert("Email đã tồn tại!");</script>';   
      }else{
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
         echo '<script>alert("Cập nhật email thành công!");</script>';  
      }
   }

   if(!empty($number)){
      $select_number = $conn->prepare("SELECT * FROM `users` WHERE number = ?");
      $select_number->execute([$number]);
      if($select_number->rowCount() > 0){
        echo '<script>alert("Số điện thoại đã tồn tại!");</script>';   
      }else{
         $update_number = $conn->prepare("UPDATE `users` SET number = ? WHERE id = ?");
         $update_number->execute([$number, $user_id]);
         echo '<script>alert("Cập nhật số điện thoại thành công!");</script>';  
      }
   }
   if(!empty($address)){
      $update_address = $conn->prepare("UPDATE `users` SET address = ? WHERE id = ?");
      $update_address->execute([$address, $user_id]);
   }
   
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
        echo '<script>alert("Mật khẩu cũ không khớp!");</script>';   
      }elseif($new_pass != $confirm_pass){
        echo '<script>alert("Mật khẩu xác nhận không đúng!");</script>';   
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $user_id]);
            echo '<script>alert("Cập nhật mật khẩu thành công!");</script>';   
          }else{
            echo '<script>alert("Vui lòng nhập lại mật khẩu mới!");</script>';   
          }
      }
   }  

}

?>
<!DOCTYPE html>
<html lang="en">

<?php include 'components/user_head.php'; ?>

<body>
	
	<!--PreLoader-->
    <div class="loader">
        <div class="loader-inner">
            <div class="circle"></div>
        </div>
    </div>
    <!--PreLoader Ends-->
	
	<!-- header -->
	<div class="top-header-area" id="sticker">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-sm-12 text-center">
					<div class="main-menu-wrap">
						<!-- logo -->
						<div class="site-logo">
							<a href="index.html">
                            <img style="width: 100%;" src="fruitkha-1.0.0/fruitkha-1.0.0/assets/img/logo.png" alt="">
							</a>
						</div>
						<!-- logo -->

						<!-- menu start -->
                        <?php include 'components/user_nav.php'; ?>

						<a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
						<div class="mobile-menu"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end header -->

	<!-- search area -->
	<div class="search-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<span class="close-btn"><i class="fas fa-window-close"></i></span>
					<div class="search-bar">
						<div class="search-bar-tablecell">
							<h3>Search For:</h3>
							<input type="text" placeholder="Keywords">
							<button type="submit">Search <i class="fas fa-search"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end search arewa -->
	
	<!-- breadcrumb-section -->
	<div class="breadcrumb-section breadcrumb-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<p>CookingFood rất vui khi được iết đến bạn!</p>
						<h1>Cập nhật tài khoản</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- check out section -->
	<div class="checkout-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="checkout-accordion-wrap">
						<div class="accordion" id="accordionExample">
						  <div class="card single-accordion">
						    <div class="card-header" id="headingOne">
						      <h5 class="mb-0">
						        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						          Thông tin tài khoản
						        </button>
						      </h5>
						    </div>

						    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
						      <div class="card-body">
						        <div class="billing-address-form">
                      <form action="" method="post">
                        <div class= "row">
                          <div class= "col-lg-6">
                            <span style= "font-size: 15px;"><strong>Tên tài khoản</strong></span>
                            <p><input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>"></p>
                          </div>
                        
                          <div class= "col-lg-6">
                            <span style= "font-size: 15px;"><strong>Email</strong></span>
                            <p><input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>"></p>
                          </div>
                        </div>
                        <div class="row">
                          <div class = "col-lg-6">
                            <span style= "font-size: 15px;"><strong>Địa chỉ</strong></span>
                            <p><input type="text" name="address" placeholder="<?= $fetch_profile['address']; ?>"></p>
                          </div>
                          
                          <div class = "col-lg-6">
                            <span style= "font-size: 15px;"><strong>Số điện thoại</strong></span>
                            <p><input type="tel" name="number" placeholder="<?= $fetch_profile['number']; ?>"></p>
                          </div>    
                        </div>
                        <div class = "row">
                          <div class = "col-lg-6">
                            <span style= "font-size: 15px;"><strong> Mật khẩu cũ</strong></span>
                            <p><input type="password" name="old_pass" placeholder="Mật khẩu cũ"></p>
                          </div>    
                          <div class = "col-lg-6">
                            <span style= "font-size: 15px;"><strong>Mật khẩu mới</strong></span>
                            <p><input type="password" name="new_pass" placeholder="Mật khẩu mới"></p>
                          </div>    
                        </div>
                                                
                        <span style= "font-size: 15px;"><strong>Xác nhận mật khẩu mới</strong></span>
                          <p><input type="password" name="confirm_pass" placeholder="Xác nhận mật khẩu mới"></p>

                          <div style ="padding-left:20px;">
                          <input style="font-family: 'Poppins', sans-serif;
									display: inline-block;
									background-color: #F28123;
									color: #fff;
									font-weight: normal;
									font-size: 1rem;
									padding: 10px 15px;"type="submit" value="Cập nhật" name="submit" class="btn">

                                </div>
                      </form>
						        </div>
                                
						      </div>
						    </div>
						  </div>
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>
	<!-- end check out section -->

	<!-- footer -->
	<?php include 'components/user_footer.php'; ?>


</body>
</html>