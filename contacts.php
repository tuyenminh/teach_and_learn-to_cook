<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['message'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);
   $subject = $_POST['subject'];
   $subject = filter_var($subject, FILTER_SANITIZE_STRING);
   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ? AND subject = ?");
   $select_message->execute([$name, $email, $number, $msg, $subject]);

   if($select_message->rowCount() > 0){
    echo '<script>alert("Tin nhắn đã được gửi");</script>';   
}else{

      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message, subject) VALUES(?,?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg, $subject]);

      echo '<script>alert("Tin nhắn đã được gửi!");</script>';   

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
						<!-- menu end -->
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
						<p>Hỗ trợ 24/7</p>
						<h1>Liên hệ với chúng tôi</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- contact form -->
	<div class="contact-from-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 mb-5 mb-lg-0">
					<div class="form-title">
						<h2>Câu hỏi của bạn?</h2>
						<p>Hãy để lại lời nhắn khi có bất cứ câu hỏi nào liên quan đến thắc mắc về các khóa học. Chúng tôi sẽ trả lời ngay cho bạn thông qua email!</p>
					</div>
				 	<div id="form_status"></div>
					<div class="contact-form">
						<form type="POST" id="fruitkha-contact" action="" method="POST" onSubmit="return valid_datas( this );">
							<p>
								<input type="text" placeholder="Tên của bạn" name="name" id="name">
								<input type="email" placeholder="Email" name="email" id="email">
							</p>
							<p>
								<input type="tel" placeholder="Số điện thoại" name="number" id="phone">
								<input type="text" placeholder="Tiêu đề" name="subject" id="subject">
							</p>
							<p><textarea name="message" id="message" cols="30" rows="10" placeholder="Lời nhắn"></textarea></p>
							<input type="hidden" name="token" value="FsWga4&@f6aw" />
							<p><input type="submit" name="send" value="Gửi"></p>
						</form>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="contact-form-wrap">
						<div class="contact-form-box">
							<h4><i class="fas fa-map"></i> Địa chỉ CookingFood</h4>
							<p>34/8, Phường Phú Hưng <br> TP Bến Tre, Bến Tre. <br> Việt Nam</p>
						</div>
						<div class="contact-form-box">
							<h4><i class="far fa-clock"></i> Giờ hoạt động</h4>
							<p>Thứ 2 - Thứ 6: 8 sáng đến 17 giờ chiều  <br> Thứ 7- Chủ nhật: 10 sáng đến 16 chiều </p>
						</div>
						<div class="contact-form-box">
							<h4><i class="fas fa-address-book"></i> Liên hệ</h4>
							<p>Số điện thoại: +84 582268858 <br> Email: cookingfood@gmail.com</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include 'components/chatbox.php'; ?>
	<!-- end contact form -->

	<!-- find our location -->
	<div class="find-location blue-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<p> <i class="fas fa-map-marker-alt"></i> Vị trí CookingFood</p>
				</div>
			</div>
		</div>
	</div>
	<!-- end find our location -->

	<!-- google map section -->
	<div class="embed-responsive embed-responsive-21by9">
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26432.42324808999!2d-118.34398767954286!3d34.09378509738966!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2bf07045279bf%3A0xf67a9a6797bdfae4!2sHollywood%2C%20Los%20Angeles%2C%20CA%2C%20USA!5e0!3m2!1sen!2sbd!4v1576846473265!5m2!1sen!2sbd" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" class="embed-responsive-item"></iframe>
	</div>
	<!-- end google map section -->


	<!-- footer -->
	<?php include 'components/user_footer.php'; ?>
	
</body>
</html>