<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};

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
						<h1>Thông tin tài khoản</h1>
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

                                        <span style= "font-size: 15px;"><strong>
                                            Tên tài khoản
                                        </strong></span>
						        		<p><input type="text" name="name" value="<?= $fetch_profile['name'] ?>"placeholder="Tên học viên"></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Email
                                        </strong></span>

						        		<p><input type="email" name="email" value="<?= $fetch_profile['email'] ?>" placeholder="Email"></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Địa chỉ
                                        </strong></span>

						        		<p><input type="text" name="address" value="<?= $fetch_profile['address'] ?>" placeholder="Địa chỉ"></p>
                                        <span style= "font-size: 15px;"><strong>
                                            Số điện thoại
                                        </strong></span>

						        		<p><input type="tel" name="number" value="<?= $fetch_profile['number'] ?>" placeholder="Số điện thoại"></p>
                  </form>
						        </div>
                                <div style ="padding-left:20px;">
                                    <a href="update_profile.php" class="boxed-btn">Cập nhật thông tin</a>
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
	<?php include 'components/chatbox.php'; ?>

	<!-- footer -->
	<?php include 'components/user_footer.php'; ?>


</body>
</html>