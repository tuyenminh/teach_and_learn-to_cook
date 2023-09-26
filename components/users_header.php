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
						<nav class="main-menu">
							<ul>
								<li class="current-list-item"><a href="index.php">Trang chủ</a>
								</li>
								<li><a href="recipe.php">Công thức nấu ăn</a></li>
								<li><a href="news.php">Tin tức</a></li>
								<li><a href="contacts.php">Liên hệ</a></li>
								<li>
									<div class="header-icons">
										<a class="shopping-cart" href="giohang.php"><i class="fas fa-shopping-cart"></i></a>
										<a class="mobile-hide search-bar-icon" href="search.php"><i class="fas fa-search"></i></a>
                                        <a> <i class = "fa fa-user"></i></a>
										<?php
                                            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                                            $select_profile->execute([$user_id]);
                                            if($select_profile->rowCount() > 0){
                                            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                        <p class="name"><?= $fetch_profile['name']; ?></p>
                                        <div class="flex">
                                            <a href="profile.php" class="btn">Hồ Sơ</a>
                                            <a href="components/user_logout.php" onclick="return confirm('Đăng xuất khỏi trang web này?');" class="delete-btn">Đăng xuất</a>
                                        </div>
                                        <p class="account">
                                            <a href="login.php">Đăng nhập</a> /
                                            <a href="register.php">Đăng kí</a>
                                        </p> 
                                        <?php
                                            }else{
                                        ?>
                                            <p class="name">Vui lòng đăng nhập</p>
                                            <a href="login.php" class="btn">Đăng nhập</a>
                                        <?php
                                        }
                                        ?>
									</div>
								</li>
							</ul>
						</nav>
						<a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
						<div class="mobile-menu"></div>
						<!-- menu end -->
					</div>
				</div>
			</div>
		</div>
	</div>